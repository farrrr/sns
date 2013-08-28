/*
Copyright 2011, KISSY UI Library v1.20dev
MIT Licensed
build time: ${build.time}
*/
/**
 * UIBase.Align
 * @author: 承玉<yiminghe@gmail.com>, 喬花<qiaohua@taobao.com>
 */
KISSY.add('uibase/align', function(S, DOM) {


    function Align() {
    }

    S.mix(Align, {
        TL: 'tl',
        TC: 'tc',
        TR: 'tr',
        CL: 'cl',
        CC: 'cc',
        CR: 'cr',
        BL: 'bl',
        BC: 'bc',
        BR: 'br'
    });

    Align.ATTRS = {
        align: {
            /*
             value:{
             node: null,         // 參考元素, falsy 值為可視區域, 'trigger' 為觸發元素, 其他為指定元素
             points: [AlignExt.CC, AlignExt.CC], // ['tr', 'tl'] 表示 overlay 的 tl 與參考節點的 tr 對齊
             offset: [0, 0]      // 有效值為 [n, m]
             }*/
        }
    };

    /**
     * 獲取 node 上的 align 對齊點 相對於頁面的座標
     * @param node
     * @param align
     */
    function getAlignOffset(node, align) {
        var Node = S.require("node/node");
        var V = align.charAt(0),
            H = align.charAt(1),
            offset, w, h, x, y;

        if (node) {
            node = Node.one(node);
            offset = node.offset();
            w = node[0].offsetWidth;
            h = node[0].offsetHeight;
        } else {
            offset = { left: DOM.scrollLeft(), top: DOM.scrollTop() };
            w = DOM['viewportWidth']();
            h = DOM['viewportHeight']();
        }

        x = offset.left;
        y = offset.top;

        if (V === 'c') {
            y += h / 2;
        } else if (V === 'b') {
            y += h;
        }

        if (H === 'c') {
            x += w / 2;
        } else if (H === 'r') {
            x += w;
        }

        return { left: x, top: y };
    }

    Align.prototype = {

        _uiSetAlign: function(v) {

            if (S.isPlainObject(v)) {
                this.align(v.node, v.points, v.offset);
            }
        },

        /**
         * 對齊 Overlay 到 node 的 points 點, 偏移 offset 處
         * @param {Element=} node 參照元素, 可取配置選項中的設定, 也可是一元素
         * @param {Array.<string>} points 對齊方式
         * @param {Array.<number>} offset 偏移
         */
        align: function(node, points, offset) {
            var self = this,
                xy,
                diff,
                p1,
                //如果沒有view，就是不區分mvc
                el = (self.get("view") || self).get('el'),
                p2;

            offset = offset || [0,0];
            xy = el.offset();

            // p1 是 node 上 points[0] 的 offset
            // p2 是 overlay 上 points[1] 的 offset
            p1 = getAlignOffset(node, points[0]);
            p2 = getAlignOffset(el, points[1]);

            diff = [p2.left - p1.left, p2.top - p1.top];
            xy = [
                xy.left - diff[0] + (+offset[0]),
                xy.top - diff[1] + (+offset[1])
            ];

            self.set('x', xy[0]);
            self.set('y', xy[1]);
        },

        /**
         * 居中顯示到可視區域, 一次性居中
         */
        center: function(node) {
            this.set('align', {
                node: node,
                points: [Align.CC, Align.CC],
                offset: [0, 0]
            });
        }
    };

    return Align;
}, {
    requires:["dom"]
});
/**
 * @module  UIBase
 * @author  承玉<yiminghe@gmail.com>,lifesinger@gmail.com
 */
KISSY.add('uibase/base', function (S, Base) {

    var UI_SET = '_uiSet',
        SRC_NODE = 'srcNode',
        ATTRS = 'ATTRS',
        HTML_PARSER = 'HTML_PARSER',
        Node = S.require("node/node"),
        Attribute = S.require("base/attribute"),
        capitalFirst = Attribute.__capitalFirst,
        noop = function() {
        };

    /*
     * UIBase for class-based component
     */
    function UIBase(config) {
        Base.apply(this, arguments);
        initHierarchy(this, config);
        config && config.autoRender && this.render();
    }

    /**
     * 模擬多繼承
     * init attr using constructors ATTRS meta info
     */
    function initHierarchy(host, config) {

        var c = host.constructor;

        while (c) {

            // 從 markup 生成相應的屬性項
            if (config &&
                config[SRC_NODE] &&
                c.HTML_PARSER) {
                if ((config[SRC_NODE] = Node.one(config[SRC_NODE])))
                    applyParser.call(host, config[SRC_NODE], c.HTML_PARSER);
            }

            c = c.superclass && c.superclass.constructor;
        }

        callMethodByHierarchy(host, "initializer", "constructor");

    }

    function callMethodByHierarchy(host, mainMethod, extMethod) {
        var c = host.constructor,
            extChains = [],
            ext,
            main,
            exts,
            t;

        // define
        while (c) {

            // 收集擴展類
            t = [];
            if ((exts = c.__ks_exts)) {
                for (var i = 0; i < exts.length; i++) {
                    ext = exts[i];
                    if (ext) {
                        if (extMethod != "constructor") {
                            //只呼叫真正自己構造器原型的定義，繼承原型鏈上的不要管
                            if (ext.prototype.hasOwnProperty(extMethod)) {
                                ext = ext.prototype[extMethod];
                            } else {
                                ext = null;
                            }
                        }
                        ext && t.push(ext);
                    }
                }
            }

            // 收集主類
            // 只呼叫真正自己構造器原型的定義，繼承原型鏈上的不要管 !important
            //所以不用自己在 renderUI 中呼叫 superclass.renderUI 了，UIBase 構造器自動搜尋
            if (c.prototype.hasOwnProperty(mainMethod) && (main = c.prototype[mainMethod])) {
                t.push(main);
            }

            // 原地 reverse
            if (t.length) {
                extChains.push.apply(extChains, t.reverse());
            }

            c = c.superclass && c.superclass.constructor;
        }

        // 初始化函數
        // 順序：父類的所有擴展類函數 -> 父類對應函數 -> 子類的所有擴展函數 -> 子類對應函數
        for (i = extChains.length - 1; i >= 0; i--) {
            extChains[i] && extChains[i].call(host);
        }
    }

    /**
     * 銷燬元件
     * 順序：子類擴展 destructor -> 子類 destructor -> 父類擴展 destructor -> 父類 destructor
     */
    function destroyHierarchy(host) {
        var c = host.constructor,
            exts,
            d,
            i;

        while (c) {
            (d = c.prototype.destructor) && d.apply(host);

            if ((exts = c.__ks_exts)) {
                for (i = exts.length - 1; i >= 0; i--) {
                    d = exts[i] && exts[i].prototype.__destructor;
                    d && d.apply(host);
                }
            }

            c = c.superclass && c.superclass.constructor;
        }
    }

    function applyParser(srcNode, parser) {
        var host = this, p, v;

        // 從 parser 中，默默設定屬性，不觸發事件
        for (p in parser) {
            if (parser.hasOwnProperty(p)) {
                v = parser[p];

                // 函數
                if (S.isFunction(v)) {
                    host.__set(p, v.call(host, srcNode));
                }
                // 單選選擇器
                else if (S['isString'](v)) {
                    host.__set(p, srcNode.one(v));
                }
                // 多選選擇器
                else if (S.isArray(v) && v[0]) {
                    host.__set(p, srcNode.all(v[0]))
                }
            }
        }
    }

    UIBase.HTML_PARSER = {};
    UIBase.ATTRS = {
        //渲染容器
        render:{
            valueFn:function() {
                return document.body;
            },
            setter:function(v) {
                if (S['isString'](v))
                    return Node.one(v);
            }
        },
        //是否已經渲染過
        rendered:{value:false}
    };

    S.extend(UIBase, Base, {

        render: function() {
            var self = this;
            if (!self.get("rendered")) {
                self._renderUI();
                self.fire('renderUI');
                callMethodByHierarchy(self, "renderUI", "__renderUI");
                self.fire('afterRenderUI');
                self._bindUI();
                self.fire('bindUI');
                callMethodByHierarchy(self, "bindUI", "__bindUI");
                self.fire('afterBindUI');
                self._syncUI();
                self.fire('syncUI');
                callMethodByHierarchy(self, "syncUI", "__syncUI");
                self.fire('afterSyncUI');
                self.set("rendered", true);
            }
        },

        /**
         * 根據屬性添加 DOM 節點
         */
        _renderUI: noop,
        renderUI: noop,

        /**
         * 根據屬性變化設定 UI
         */
        _bindUI: function() {
            var self = this,
                attrs = self.__attrs,
                attr, m;

            for (attr in attrs) {
                if (attrs.hasOwnProperty(attr)) {
                    m = UI_SET + capitalFirst(attr);
                    if (self[m]) {
                        // 自動繫結事件到對應函數
                        (function(attr, m) {
                            self.on('after' + capitalFirst(attr) + 'Change', function(ev) {
                                self[m](ev.newVal, ev);
                            });
                        })(attr, m);
                    }
                }
            }
        },
        bindUI: noop,

        /**
         * 根據當前（初始化）狀態來設定 UI
         */
        _syncUI: function() {
            var self = this,
                attrs = self.__getDefAttrs();
            for (var a in attrs) {
                if (attrs.hasOwnProperty(a)) {
                    var m = UI_SET + capitalFirst(a);
                    //存在方法，並且使用者設定了初始值或者存在預設值，就同步狀態
                    if (self[m] && self.get(a) !== undefined) {
                        self[m](self.get(a));
                    }
                }
            }
        },
        syncUI: noop,

        destroy: function() {
            destroyHierarchy(this);
            this.fire('destroy');
            this.detach();
        }
    });

    /**
     * 根據基類以及擴展類得到新類
     * @param {function} base 基類
     * @param exts 擴展類
     * @param {Object} px 原型 mix 物件
     * @param {Object} sx 靜態 mix 物件
     */
    UIBase.create = function(base, exts, px, sx) {
        if (S.isArray(base)) {
            sx = px;
            px = exts;
            exts = base;
            base = UIBase;
        }
        base = base || UIBase;
        if (S['isObject'](exts)) {
            sx = px;
            px = exts;
            exts = [];
        }

        function C() {
            UIBase.apply(this, arguments);
        }

        S.extend(C, base, px, sx);

        if (exts) {
            C.__ks_exts = exts;

            S.each(exts, function(ext) {
                if (!ext)return;
                // 合併 ATTRS/HTML_PARSER 到主類
                S.each([ATTRS, HTML_PARSER], function(K) {
                    if (ext[K]) {
                        C[K] = C[K] || {};
                        // 不覆蓋主類上的定義
                        deepMix(C[K], ext[K]);
                    }
                });

                // 合併功能程式碼到主類，不覆蓋
                S.augment(C, ext, false);
            });
        }

        return C;
    };
    function deepMix(r, s) {
        if (!s) return r;
        for (var p in s) {
            // 如果屬性是物件，接著遞迴進行
            if (S['isObject'](s[p]) && S['isObject'](r[p])) {
                deepMix(r[p], s[p]);
            } else if (!(p in r)) {
                r[p] = s[p];
            }
        }
    }

    return UIBase;
}, {
    requires:["base","dom","node"]
});
/**
 * UIBase.Box
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add('uibase/box', function(S) {


    function Box() {
    }

    Box.ATTRS = {
        html: {
            view:true
        },
        width:{
            view:true
        },
        height:{
            view:true
        },
        elCls:{
            view:true
        },
        elStyle:{
            view:true
        },
        elAttrs:{
            //其他屬性
            view:true
        },
        elOrder:{},
        el:{
            getter:function() {
                return this.get("view")&&this.get("view").get("el");
            }
        }
    };

    Box.prototype = {};

    return Box;
});
/**
 * UIBase.Box
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add('uibase/boxrender', function(S, Node) {


    function Box() {
    }

    S.mix(Box, {
        APPEND:1,
        INSERT:0
    });

    Box.ATTRS = {
        el: {
            //容器元素
            setter:function(v) {
                var Node = S.require("node/node");
                if (S['isString'](v))
                    return Node.one(v);
            }
        },
        elCls: {
            // 容器的 class
        },
        elStyle:{
            //容器的行內樣式
        },
        width: {
            // 寬度
        },
        height: {
            // 高度
        },
        elTagName:{
            //生成標簽名字
            value:"div"
        },
        elAttrs:{
            //其他屬性
        },
        elOrder:{
            //插入容器位置
            //0 : prepend
            //1 : append
            value:1
        },
        html: {}
    };

    Box.construct = constructEl;

    function constructEl(cls, style, width, height, tag, attrs) {
        style = style || {};

        if (width) {
            style.width = width;
        }

        if (height) {
            style.height = height;
        }

        var styleStr = '';

        for (var s in style) {
            if (style.hasOwnProperty(s)) {
                styleStr += s + ":" + style[s] + ";";
            }
        }

        var attrStr = '';

        for (var a in attrs) {
            if (attrs.hasOwnProperty(a)) {
                attrStr += " " + a + "='" + attrs[a] + "'" + " ";
            }
        }

        var ret = "<" + tag + (styleStr ? (" style='" + styleStr + "' ") : "")
            + attrStr + (cls ? (" class='" + cls + "' ") : "")
            + ">";
        return ret;
    }

    Box.HTML_PARSER = {
        el:function(srcNode) {
            return srcNode;
        }
    };

    Box.prototype = {

        __renderUI:function() {
            var self = this,
                render = self.get("render"),
                el = self.get("el");
            render = new Node(render);
            if (!el) {
                el = new Node(constructEl(self.get("elCls"),
                    self.get("elStyle"),
                    self.get("width"),
                    self.get("height"),
                    self.get("elTagName"),
                    self.get("elAttrs")));
                if (self.get("elOrder")) {
                    render.append(el);
                } else {
                    render.prepend(el);
                }
                self.set("el", el);
            }
        },
        _uiSetElAttrs:function(attrs) {
            this.get("el").attr(attrs);
        },
        _uiSetElCls:function(cls) {
            this.get("el").addClass(cls);
        },

        _uiSetElStyle:function(style) {
            this.get("el").css(style);
        },

        _uiSetWidth:function(w) {
            this.get("el").width(w);
        },

        _uiSetHeight:function(h) {
            //S.log("_uiSetHeight");
            var self = this;
            self.get("el").height(h);
        },

        _uiSetHtml:function(c) {
            this.get("el").html(c);
        },

        __destructor:function() {
            //S.log("box __destructor");
            var el = this.get("el");
            if (el) {
                el.detach();
                el.remove();
            }
        }
    };

    return Box;
}, {
    requires:['node']
});
/**
 * close extension for kissy dialog
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/close", function(S) {
    function Close() {
    }

    Close.ATTRS = {
        closable: {             // 是否需要關閉按鈕
            value: true,
            view:true
        }
    };

    Close.prototype = {

        __bindUI:function() {

            var self = this,
                closeBtn = self.get("view").get("closeBtn");
            closeBtn && closeBtn.on("click", function(ev) {
                self.hide();
                ev.halt();
            });
        }
    };
    return Close;

});/**
 * close extension for kissy dialog
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/closerender", function(S) {

    var CLS_PREFIX = 'ext-';

    function Close() {
    }

    Close.ATTRS = {
        closable: {             // 是否需要關閉按鈕
            value: true
        },
        closeBtn:{}
    };

    Close.HTML_PARSER = {
        closeBtn:function(el) {
            return el.one("." + this.get("prefixCls") + CLS_PREFIX + 'close');
        }
    };

    Close.prototype = {
        _uiSetClosable:function(v) {
            var self = this,
                closeBtn = self.get("closeBtn");
            if (closeBtn) {
                if (v) {
                    closeBtn.css("display", "");
                } else {
                    closeBtn.css("display", "none");
                }
            }
        },
        __renderUI:function() {
            var Node = S.require("node/node");
            var self = this,
                closeBtn = self.get("closeBtn"),
                el = self.get("contentEl");

            if (!closeBtn &&
                el) {
                closeBtn = new Node("<a " +
                    "href='#' " +
                    "class='" + this.get("prefixCls") +CLS_PREFIX + "close" + "'>" +
                    "<span class='" +
                    this.get("prefixCls") +CLS_PREFIX + "close-x" +
                    "'>X</span>" +
                    "</a>")
                    .appendTo(el);
                self.set("closeBtn", closeBtn);
            }
        },

        __destructor:function() {

            var self = this,
                closeBtn = self.get("closeBtn");
            closeBtn && closeBtn.detach();
        }
    };
    return Close;

});/**
 * constrain extension for kissy
 * @author: 承玉<yiminghe@gmail.com>, 喬花<qiaohua@taobao.com>
 */
KISSY.add("uibase/constrain", function(S, DOM) {
    var Node = S.require("node/node");

    function Constrain() {

    }

    Constrain.ATTRS = {
        constrain:{
            //不限制
            //true:viewport限制
            //node:限制在節點範圍
            value:false
        }
    };

    /**
     * 獲取受限區域的寬高, 位置
     * @return {Object | undefined} {left: 0, top: 0, maxLeft: 100, maxTop: 100}
     */
    function _getConstrainRegion(constrain) {
        var ret;
        if (!constrain) return ret;
        var el = this.get("view").get("el");
        if (constrain !== true) {
            constrain = Node.one(constrain);
            ret = constrain.offset();
            S.mix(ret, {
                maxLeft: ret.left + constrain[0].offsetWidth - el[0].offsetWidth,
                maxTop: ret.top + constrain[0].offsetHeight - el[0].offsetHeight
            });
        }
        // 沒有指定 constrain, 表示受限於可視區域
        else {
            //不要使用 viewportWidth()
            //The innerWidth attribute, on getting,
            //must return the viewport width including the size of a rendered scroll bar (if any).
            //On getting, the clientWidth attribute returns the viewport width
            //excluding the size of a rendered scroll bar (if any)
            //  if the element is the root element 
            var vWidth = document.documentElement.clientWidth;
            ret = { left: DOM.scrollLeft(), top: DOM.scrollTop() };
            S.mix(ret, {
                maxLeft: ret.left + vWidth - el[0].offsetWidth,
                maxTop: ret.top + DOM['viewportHeight']() - el[0].offsetHeight
            });
        }

        return ret;
    }

    Constrain.prototype = {

        __renderUI:function() {
            //S.log("_renderUIConstrain");
            var self = this,
                attrs = self.__getDefAttrs(),
                xAttr = attrs["x"],
                yAttr = attrs["y"],
                oriXSetter = xAttr["setter"],
                oriYSetter = yAttr["setter"];
            xAttr.setter = function(v) {
                var r = oriXSetter && oriXSetter(v);
                if (r === undefined) {
                    r = v;
                }
                if (!self.get("constrain")) return r;
                var _ConstrainExtRegion = _getConstrainRegion.call(
                    self, self.get("constrain"));
                return Math.min(Math.max(r,
                    _ConstrainExtRegion.left),
                    _ConstrainExtRegion.maxLeft);
            };
            yAttr.setter = function(v) {
                var r = oriYSetter && oriYSetter(v);
                if (r === undefined) {
                    r = v;
                }
                if (!self.get("constrain")) return r;
                var _ConstrainExtRegion = _getConstrainRegion.call(
                    self, self.get("constrain"));
                return Math.min(Math.max(r,
                    _ConstrainExtRegion.top),
                    _ConstrainExtRegion.maxTop);
            };
            self.addAttr("x", xAttr);
            self.addAttr("y", yAttr);
        }
    };


    return Constrain;

}, {
    requires:["dom","node"]
});/**
 * 裡層包裹層定義，適合mask以及shim
 * @author:yiminghe@gmail.com
 */
KISSY.add("uibase/contentbox", function(S) {

    function ContentBox() {
    }

    ContentBox.ATTRS = {
        //層內容
        content:{
            view:true
        },
        contentEl:{
            getter:function() {
                return this.get("view") && this.get("view").get("contentEl");
            }
        },

        contentElAttrs:{
            view:true
        },
        contentElStyle:{
            view:true
        },
        contentTagName:{
            view:true
        }
    };


    ContentBox.prototype = {    };

    return ContentBox;
});/**
 * 裡層包裹層定義，適合mask以及shim
 * @author:yiminghe@gmail.com
 */
KISSY.add("uibase/contentboxrender", function(S, Node, BoxRender) {

    function ContentBox() {
    }

    ContentBox.ATTRS = {
        //內容容器節點
        contentEl:{},
        contentElAttrs:{},
        contentElStyle:{},
        contentTagName:{value:"div"},
        //層內容
        content:{}
    };


    ContentBox.HTML_PARSER = {
        contentEl:function(el) {
            return el.one("." + this.get("prefixCls") + "contentbox");
        }
    };

    var constructEl = BoxRender.construct;

    ContentBox.prototype = {

        __renderUI:function() {

            var self = this,
                contentEl = self.get("contentEl"),
                el = self.get("el");
            if (!contentEl) {
                var elChildren = S.makeArray(el[0].childNodes);
                contentEl = new Node(constructEl(this.get("prefixCls") + "contentbox",
                    self.get("contentElStyle"),
                    undefined,
                    undefined,
                    self.get("contentTagName"),
                    self.get("contentElAttrs"))).appendTo(el);
                for (var i = 0; i < elChildren.length; i++) {
                    contentEl.append(elChildren[i]);
                }
                self.set("contentEl", contentEl);
            }
        },

        _uiSetContentElAttrs:function(attrs) {
            attrs && this.get("contentEl").attr(attrs);
        },
        _uiSetContentElStyle:function(v) {
            v && this.get("contentEl").css(v);
        },
        _uiSetContent:function(c) {
            if (S['isString'](c)) {
                this.get("contentEl").html(c);
            } else if (c !== undefined) {
                this.get("contentEl").html("");
                this.get("contentEl").append(c);
            }
        }
    };

    return ContentBox;
}, {
    requires:["node","./boxrender"]
});/**
 * drag extension for position
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/drag", function(S) {


    function Drag() {
    }

    Drag.ATTRS = {
        handlers:{value:[]},
        draggable:{value:true}
    };

    Drag.prototype = {

        _uiSetHandlers:function(v) {
            if (v && v.length > 0 && this.__drag)
                this.__drag.set("handlers", v);
        },

        __bindUI:function() {
            var Draggable = S.require("dd/draggable");
            var self = this,
                el = self.get("view").get("el");
            if (self.get("draggable") && Draggable)
                self.__drag = new Draggable({
                    node:el,
                    handlers:self.get("handlers")
                });
        },

        _uiSetDraggable:function(v) {

            var self = this,
                d = self.__drag;
            if (!d) return;
            if (v) {
                d.detach("drag");
                d.on("drag", self._dragExtAction, self);
            } else {
                d.detach("drag");
            }
        },

        _dragExtAction:function(offset) {
            this.set("xy", [offset.left,offset.top])
        },
        /**
         *
         */
        __destructor:function() {
            //S.log("DragExt __destructor");
            var d = this.__drag;
            d && d.destroy();
        }

    };

    return Drag;

});/**
 * loading mask support for overlay
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/loading", function(S) {

    function Loading() {
    }

    Loading.prototype = {
        loading:function() {
            this.get("view").loading();
        },

        unloading:function() {
            this.get("view").unloading();
        }
    };

    return Loading;

});/**
 * loading mask support for overlay
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/loadingrender", function(S, Node) {

    function Loading() {
    }

    Loading.prototype = {
        loading:function() {
            var self = this;
            if (!self._loadingExtEl) {
                self._loadingExtEl = new Node("<div " +
                    "class='" +
                    this.get("prefixCls") +
                    "ext-loading'" +
                    " style='position: absolute;" +
                    "border: none;" +
                    "width: 100%;" +
                    "top: 0;" +
                    "left: 0;" +
                    "z-index: 99999;" +
                    "height:100%;" +
                    "*height: expression(this.parentNode.offsetHeight);" + "'>")
                    .appendTo(self.get("el"));
            }
            self._loadingExtEl.show();
        },

        unloading:function() {
            var lel = this._loadingExtEl;
            lel && lel.hide();
        }
    };

    return Loading;

}, {
    requires:['node']
});/**
 * mask extension for kissy
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/mask", function(S) {


    function Mask() {
    }

    Mask.ATTRS = {
        mask:{
            value:false
        }
    };

    Mask.prototype = {

        _uiSetMask:function(v) {
            var self = this;
            if (v) {
                self.on("show", self.get("view")._maskExtShow, self.get("view"));
                self.on("hide", self.get("view")._maskExtHide, self.get("view"));
            } else {
                self.detach("show", self.get("view")._maskExtShow, self.get("view"));
                self.detach("hide", self.get("view")._maskExtHide, self.get("view"));
            }
        }
    };

    return Mask;
}, {requires:["ua"]});/**
 * mask extension for kissy
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/maskrender", function(S) {

    /**
     * 多 position 共享一個遮罩
     */
    var mask,
        num = 0;


    function initMask() {
        var UA = S.require("ua"),Node = S.require("node/node"),DOM = S.require("dom");
        mask = new Node("<div class='" +
            this.get("prefixCls") + "ext-mask'>").prependTo(document.body);
        mask.css({
            "position":"absolute",
            left:0,
            top:0,
            width:UA['ie'] == 6 ? DOM['docWidth']() : "100%",
            "height": DOM['docHeight']()
        });
        if (UA['ie'] == 6) {
            mask.append("<" + "iframe style='width:100%;" +
                "height:expression(this.parentNode.offsetHeight);" +
                "filter:alpha(opacity=0);" +
                "z-index:-1;'>");
        }
    }

    function Mask() {
        //S.log("mask init");
    }


    Mask.prototype = {

        _maskExtShow:function() {
            if (!mask) {
                initMask.call(this);
            }
            mask.css({
                "z-index":this.get("zIndex") - 1
            });
            num++;
            mask.css("display", "");
        },

        _maskExtHide:function() {
            num--;
            if (num <= 0) num = 0;
            if (!num)
                mask && mask.css("display", "none");
        }

    };

    return Mask;
}, {requires:["ua"]});/**
 * position and visible extension，可定位的隱藏層
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/position", function(S, DOM, Event) {


    var doc = document ,
        KEYDOWN = "keydown";

    function Position() {
    }

    Position.ATTRS = {
        x: {
            view:true,
            // 水平方向絕對位置
            valueFn:function() {
                //初始化 xy，結果呼叫了 set("x") 裡面又呼叫了 get("x")
                //這時還沒有渲染，尚沒有 view，必須判斷
                return this.get("view") && this.get("view").get("x");
            }
        },
        y: {
            view:true,
            // 垂直方向絕對位置
            // 水平方向絕對位置
            valueFn:function() {
                return this.get("view") && this.get("view").get("y");
            }
        },
        xy: {
            // 相對 page 定位, 有效值為 [n, m], 為 null 時, 選 align 設定
            setter: function(v) {

                var self = this,
                    xy = S.makeArray(v);

                /*
                 屬性內分發特別注意：
                 xy -> x,y

                 */
                if (xy.length) {
                    xy[0] && self.set("x", xy[0]);
                    xy[1] && self.set("y", xy[1]);
                }
                return v;
            },
            /**
             * xy 純中轉作用
             */
            getter:function() {
                return [this.get("x"),this.get("y")];
            }
        },
        zIndex: {
            view:true
        },
        visible:{}
    };


    Position.prototype = {

        _uiSetVisible:function(isVisible) {
            var self = this;
            this.get("view").set("visible", isVisible);
            self[isVisible ? "_bindKey" : "_unbindKey" ]();
            self.fire(isVisible ? "show" : "hide");
        },
        /**
         * 顯示/隱藏時繫結的事件
         */
        _bindKey: function() {
            Event.on(doc, KEYDOWN, this._esc, this);
        },

        _unbindKey: function() {
            Event.remove(doc, KEYDOWN, this._esc, this);
        },

        _esc: function(e) {
            if (e.keyCode === 27) this.hide();
        },
        /**
         * 移動到絕對位置上, move(x, y) or move(x) or move([x, y])
         * @param {number|Array.<number>} x
         * @param {number=} y
         */
        move: function(x, y) {
            var self = this;
            if (S.isArray(x)) {
                y = x[1];
                x = x[0];
            }
            self.set("xy", [x,y]);
        },

        /**
         * 顯示 Overlay
         */
        show: function() {
            this.render();
            this.set("visible", true);
        },

        /**
         * 隱藏
         */
        hide: function() {
            this.set("visible", false);
        }

    };

    return Position;
}, {
    requires:["dom","event"]
});/**
 * position and visible extension，可定位的隱藏層
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/positionrender", function() {

    function Position() {
    }

    Position.ATTRS = {
        x: {
            // 水平方向絕對位置
            valueFn:function() {
                return this.get("el") && this.get("el").offset().left;
            }
        },
        y: {
            // 垂直方向絕對位置
            valueFn:function() {
                return this.get("el") && this.get("el").offset().top;
            }
        },
        zIndex: {
            value: 9999
        },
        visible:{}
    };


    Position.prototype = {

        __renderUI:function() {
            var el = this.get("el");
            el.addClass(this.get("prefixCls") + "ext-position");
            el.css({
                display: "",
                left:-9999,
                top:-9999,
                bottom:"",
                right:""
            });
        },

        _uiSetZIndex:function(x) {
            this.get("el").css("z-index", x);
        },
        _uiSetX:function(x) {
            this.get("el").offset({
                left:x
            });
        },
        _uiSetY:function(y) {
            this.get("el").offset({
                top:y
            });
        },
        _uiSetVisible:function(isVisible) {
            this.get("el").css("visibility", isVisible ? "visible" : "hidden");
        },

        show:function() {
            this.render();
            this.set("visible", true);
        },
        hide:function() {
            this.set("visible", false);
        }
    };

    return Position;
});KISSY.add("uibase/resize", function(S) {
    function Resize() {
    }

    Resize.ATTRS = {
        resize:{
            value:{
            }
        }
    };

    Resize.prototype = {
        __destructor:function() {
            self.resizer && self.resizer.destroy();
        },
        _uiSetResize:function(v) {

            var Resizable = S.require("resizable"),self = this;
            if (Resizable) {
                self.resizer && self.resizer.destroy();
                v.node = self.get("view").get("el");
                v.autoRender = true;
                if (v.handlers) {
                    self.resizer = new Resizable(v);
                }
            }

        }
    };
    return Resize;
});/**
 * shim for ie6 ,require box-ext
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/shimrender", function(S) {

    function Shim() {
        //S.log("shim init");
    }


    Shim.ATTRS = {
        shim:{
            value:true
        }
    };
    Shim.prototype = {

        _uiSetShim:function(v) {
            var Node = S.require("node/node");
            var self = this,el = self.get("el");
            if (v && !self.__shimEl) {
                self.__shimEl = new Node("<" + "iframe style='position: absolute;" +
                    "border: none;" +
                    "width: expression(this.parentNode.offsetWidth);" +
                    "top: 0;" +
                    "opacity: 0;" +
                    "filter: alpha(opacity=0);" +
                    "left: 0;" +
                    "z-index: -1;" +
                    "height: expression(this.parentNode.offsetHeight);" + "'>");
                el.prepend(self.__shimEl);
            } else if (!v && self.__shimEl) {
                self.__shimEl.remove();
                delete self.__shimEl;
            }
        }
    };
    return Shim;
});/**
 * support standard mod for component
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/stdmod", function(S) {


    function StdMod() {
    }

    StdMod.ATTRS = {
        header:{
            getter:function() {
                return this.get("view") && this.get("view").get("header");
            }
        },
        body:{
            getter:function() {
                return this.get("view") && this.get("view").get("body");
            }
        },
        footer:{
            getter:function() {
                return this.get("view") && this.get("view").get("footer");
            }
        },
        bodyStyle:{
            view:true
        },
        footerStyle:{
            view:true
        },
        headerStyle:{
            view:true
        },
        headerContent:{
            view:true
        },
        bodyContent:{
            view:true
        },
        footerContent:{
            view:true
        }
    };


    StdMod.prototype = {};

    return StdMod;

});/**
 * support standard mod for component
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("uibase/stdmodrender", function(S, Node, undefined) {


    var CLS_PREFIX = "stdmod-";

    function StdMod() {
    }

    StdMod.ATTRS = {
        header:{
        },
        body:{
        },
        footer:{
        },
        bodyStyle:{
        },
        footerStyle:{

        },
        headerStyle:{

        },
        headerContent:{},
        bodyContent:{},
        footerContent:{}
    };

    StdMod.HTML_PARSER = {
        header:function(el) {
            return el.one("." + this.get("prefixCls") + CLS_PREFIX + "header");
        },
        body:function(el) {
            return el.one("." + this.get("prefixCls") + CLS_PREFIX + "body");
        },
        footer:function(el) {
            return el.one("." + this.get("prefixCls") + CLS_PREFIX + "footer");
        }
    };

    function renderUI(self, part) {
        var el = self.get("contentEl"),
            partEl = self.get(part);
        if (!partEl) {
            partEl = new Node("<div class='" + self.get("prefixCls") +CLS_PREFIX + part + "'>")
                .appendTo(el);
            self.set(part, partEl);
        }
    }

    StdMod.prototype = {

        _setStdModContent:function(part, v) {
            if (S['isString'](v)) {
                this.get(part).html(v);
            } else {
                this.get(part).html("");
                this.get(part).append(v);
            }
        },
        _uiSetBodyStyle:function(v) {

            this.get("body").css(v);

        },
        _uiSetHeaderStyle:function(v) {

            this.get("header").css(v);

        },
        _uiSetFooterStyle:function(v) {

            this.get("footer").css(v);
        },
        _uiSetBodyContent:function(v) {
            this._setStdModContent("body", v);
        },
        _uiSetHeaderContent:function(v) {
            this._setStdModContent("header", v);
        },
        _uiSetFooterContent:function(v) {
            this._setStdModContent("footer", v);
        },
        __renderUI:function() {
            renderUI(this, "header");
            renderUI(this, "body");
            renderUI(this, "footer");
        }
    };

    return StdMod;

}, {
    requires:['node']
});KISSY.add("uibase", function(S, UIBase, Align, Box, BoxRender, Close, CloseRender, Contrain, Contentbox, ContentboxRender, Drag, Loading, LoadingRender, Mask, MaskRender, Position, PositionRender, ShimRender, Resize, StdMod, StdModRender) {
    Close.Render = CloseRender;
    Loading.Render = LoadingRender;
    Mask.Render = MaskRender;
    Position.Render = PositionRender;
    StdMod.Render = StdModRender;
    Box.Render = BoxRender;
    Contentbox.Render = ContentboxRender;
    S.mix(UIBase, {
        Align:Align,
        Box:Box,
        Close:Close,
        Contrain:Contrain,
        Contentbox:Contentbox,
        Drag:Drag,
        Loading:Loading,
        Mask:Mask,
        Position:Position,
        Shim:{
            Render:ShimRender
        },
        Resize:Resize,
        StdMod:StdMod
    });
    S.UIBase = UIBase;
    return UIBase;
}, {
    requires:["uibase/base",
        "uibase/align",
        "uibase/box",
        "uibase/boxrender",
        "uibase/close",
        "uibase/closerender",
        "uibase/constrain",
        "uibase/contentbox",
        "uibase/contentboxrender",
        "uibase/drag",
        "uibase/loading",
        "uibase/loadingrender",
        "uibase/mask",
        "uibase/maskrender",
        "uibase/position",
        "uibase/positionrender",
        "uibase/shimrender",
        "uibase/resize",
        "uibase/stdmod",
        "uibase/stdmodrender"]
});
