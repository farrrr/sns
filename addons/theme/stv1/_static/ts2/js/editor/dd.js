/*
Copyright 2011, KISSY UI Library v1.20dev
MIT Licensed
build time: ${build.time}
*/
/**
 * dd support for kissy , dd objects central management module
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add('dd/ddm', function(S, DOM, Event, N, Base) {

    var doc = document,
        Node = S.require("node/node"),
        SHIM_ZINDEX = 999999;

    function DDM() {
        DDM.superclass.constructor.apply(this, arguments);
        this._init();
    }

    DDM.ATTRS = {
        prefixCls:{
            value:"ks-dd-"
        },
        /**
         * mousedown 後 buffer 觸發時間  timeThred
         */
        bufferTime: { value: 200 },

        /**
         * 當前啟用的拖動物件，在同一時間只有一個值，所以不是陣列
         */
        activeDrag: {},

        /**
         *當前啟用的drop物件，在同一時間只有一個值
         */
        activeDrop:{},
        /**
         * 所有註冊的可被防止物件，統一管理
         */
        drops:{
            value:[]
        }
    };

    /*
     負責拖動涉及的全局事件：
     1.全局統一的滑鼠移動監控
     2.全局統一的滑鼠彈起監控，用來通知當前拖動物件停止
     3.為了跨越 iframe 而統一在底下的遮罩層
     */
    S.extend(DDM, Base, {

        _regDrop:function(d) {
            this.get("drops").push(d);
        },

        _unregDrop:function(d) {
            var index = S.indexOf(d, this.get("drops"));
            if (index != -1) {
                this.get("drops").splice(index, 1);
            }
        },

        _init: function() {
            var self = this;
            self._showShimMove = throttle(self._move, self, 30);
        },

        /*
         全局滑鼠移動事件通知當前拖動物件正在移動
         注意：chrome8: click 時 mousedown-mousemove-mouseup-click 也會觸發 mousemove
         */
        _move: function(ev) {
            var activeDrag = this.get('activeDrag');
            //S.log("move");
            if (!activeDrag) return;
            //防止 ie 選擇到字
            ev.preventDefault();
            activeDrag._move(ev);
            /**
             * 獲得當前的啟用drop
             */
            this._notifyDropsMove(ev);
        },

        _notifyDropsMove:function(ev) {
            var activeDrag = this.get("activeDrag"),mode = activeDrag.get("mode");
            var drops = this.get("drops");
            var activeDrop,
                vArea = 0,
                dragRegion = region(activeDrag.get("node")),
                dragArea = area(dragRegion);

            S.each(drops, function(drop) {
                var node = drop.getNodeFromTarget(ev);
               
                if (!node || node[0] == activeDrag.get("dragNode")[0])
                    return;
                var a;
                if (mode == "point") {
                    //取滑鼠所在的 drop 區域
                    if (inNodeByPointer(node, activeDrag.mousePos)) {
                        activeDrop = drop;
                        return false;
                    }

                } else if (mode == "intersect") {
                    //取一個和activeDrag交集最大的drop區域
                    a = area(intersect(dragRegion, region(node)));
                    if (a > vArea) {
                        vArea = a;
                        activeDrop = drop;
                    }

                } else if (mode == "strict") {
                    //drag 全部在 drop 裡面
                    a = area(intersect(dragRegion, region(node)));
                    if (a == dragArea) {
                        activeDrop = drop;
                        return false;
                    }
                }
            });
            var oldDrop = this.get("activeDrop");
            if (oldDrop && oldDrop != activeDrop) {
                oldDrop._handleOut(ev);
            }
            if (activeDrop) {
                activeDrop._handleOver(ev);
            } else {
                activeDrag.get("node").removeClass(this.get("prefixCls") + "drag-over");
                this.set("activeDrop", null);
            }
        },

        _deactivateDrops:function() {
            var activeDrag = this.get("activeDrag"),
                activeDrop = this.get("activeDrop");
            activeDrag.get("node").removeClass(this.get("prefixCls") + "drag-over");
            if (activeDrop) {
                var ret = { drag: activeDrag, drop: activeDrop};
                activeDrop.get("node").removeClass(this.get("prefixCls") + "drop-over");
                activeDrop.fire('drophit', ret);
                activeDrag.fire('dragdrophit', ret)
                this.fire("drophit", ret);
                this.fire("dragdrophit", ret);
            } else {
                activeDrag.fire('dragdropmiss', {
                    drag:activeDrag
                });
                this.fire("dragdropmiss", {
                    drag:activeDrag
                });
            }
        },

        /**
         * 當前拖動物件通知全局：我要開始啦
         * 全局設定當前拖動物件，
         * 還要根據配置進行 buffer 處理
         * @param drag
         */
        _start: function(drag) {
            var self = this,
                bufferTime = self.get("bufferTime") || 0;

            //事件先要註冊好，防止點選，導致 mouseup 時還沒註冊事件
            self._registerEvent();

            //是否中央管理，強制限制拖放延遲
            if (bufferTime) {
                self._bufferTimer = setTimeout(function() {
                    self._bufferStart(drag);
                }, bufferTime);
            } else {
                self._bufferStart(drag);
            }
        },

        _bufferStart: function(drag) {
            var self = this;
            self.set('activeDrag', drag);

            //真正開始移動了才啟用墊片
            if (drag.get("shim"))
                self._activeShim();

            drag._start();
            drag.get("dragNode").addClass(this.get("prefixCls") + "dragging");
        },

        /**
         * 全局通知當前拖動物件：你結束拖動了！
         * @param ev
         */
        _end: function(ev) {
            var self = this,
                activeDrag = self.get("activeDrag");
            self._unregisterEvent();
            if (self._bufferTimer) {
                clearTimeout(self._bufferTimer);
                self._bufferTimer = null;
            }
            self._shim && self._shim.css({
                display:"none"
            });

            if (!activeDrag) return;
            activeDrag._end(ev);
            activeDrag.get("dragNode").removeClass(this.get("prefixCls") + "dragging");
            //處理 drop，看看到底是否有 drop 命中
            this._deactivateDrops(ev);
            self.set("activeDrag", null);
            self.set("activeDrop", null);
        },

        /**
         * 墊片只需創建一次
         */
        _activeShim: function() {
            var self = this,doc = document;
            //創造墊片，防止進入iframe，外面document監聽不到 mousedown/up/move
            self._shim = new Node("<div " +
                "style='" +
                //red for debug
                "background-color:red;" +
                "position:absolute;" +
                "left:0;" +
                "width:100%;" +
                "top:0;" +
                "cursor:move;" +
                "z-index:" +
                //覆蓋iframe上面即可
                SHIM_ZINDEX
                + ";" +
                "'></div>").appendTo(doc.body);
            //0.5 for debug
            self._shim.css("opacity", 0);
            self._activeShim = self._showShim;
            self._showShim();
        },

        _showShim: function() {
            var self = this;
            self._shim.css({
                display: "",
                height: DOM['docHeight']()
            });
        },

        /**
         * 開始時註冊全局監聽事件
         */
        _registerEvent: function() {
            var self = this;
            Event.on(doc, 'mouseup', self._end, self);
            Event.on(doc, 'mousemove', self._showShimMove, self);
        },

        /**
         * 結束時需要取消掉，防止平時無謂的監聽
         */
        _unregisterEvent: function() {
            var self = this;
            Event.remove(doc, 'mousemove', self._showShimMove, self);
            Event.remove(doc, 'mouseup', self._end, self);
        }
    });


    /**
     * Throttles a call to a method based on the time between calls. from YUI
     * @method throttle
     * @for KISSY
     * @param fn {function} The function call to throttle.
     * @param ms {int} The number of milliseconds to throttle the method call. Defaults to 150
     * @return {function} Returns a wrapped function that calls fn throttled.
     * ! Based on work by Simon Willison: http://gist.github.com/292562
     */
    function throttle(fn, scope, ms) {
        ms = ms || 150;

        if (ms === -1) {
            return (function() {
                fn.apply(scope, arguments);
            });
        }

        var last = S.now();
        return (function() {
            var now = S.now();
            if (now - last > ms) {
                last = now;
                fn.apply(scope, arguments);
            }
        });
    }

    function region(node) {
        var offset = node.offset();
        return {
            left:offset.left,
            right:offset.left + node[0].offsetWidth,
            top:offset.top,
            bottom:offset.top + node[0].offsetHeight
        };
    }

    function inRegion(region, pointer) {

        return region.left <= pointer.left
            && region.right >= pointer.left
            && region.top <= pointer.top
            && region.bottom >= pointer.top;
    }

    function area(region) {
        if (region.top >= region.bottom || region.left >= region.right) return 0;
        return (region.right - region.left) * (region.bottom - region.top);
    }

    function intersect(r1, r2) {
        var t = Math.max(r1.top, r2.top),
            r = Math.min(r1.right, r2.right),
            b = Math.min(r1.bottom, r2.bottom),
            l = Math.max(r1.left, r2.left);
        return {
            left:l,
            right:r,
            top:t,
            bottom:b
        };
    }

    function inNodeByPointer(node, point) {
        return inRegion(region(node), point);
    }

    var ddm = new DDM();
    ddm.inRegion = inRegion;
    ddm.region = region;
    return ddm;
}, {
    requires:["dom","event","node","base"]
});
/**
 * dd support for kissy, drag for dd
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add('dd/draggable', function(S, UA, Node, Base, DDM) {

    /*
     拖放純功能類
     */
    function Draggable() {
        Draggable.superclass.constructor.apply(this, arguments);
        this._init();
    }

    Draggable.POINT = "pointer";
    Draggable.INTERSECT = "intersect";
    Draggable.STRICT = "strict";

    Draggable.ATTRS = {
        /**
         * 拖放節點，可能指向 proxy node
         */
        node: {
            setter:function(v) {
                return Node.one(v);
            }
        },
        /*
         真實的節點
         */
        dragNode:{},

        /**
         * 是否需要遮罩跨越iframe
         */
        shim:{
            value:true
        },

        /**
         * handler 陣列，注意暫時必須在 node 裡面
         */
        handlers:{
            value:[]
        },
        cursor:{
            value:"move"
        },

        mode:{
            /**
             * @enum point,intersect,strict
             * @description
             *  In point mode, a Drop is targeted by the cursor being over the Target
             *  In intersect mode, a Drop is targeted by "part" of the drag node being over the Target
             *  In strict mode, a Drop is targeted by the "entire" drag node being over the Target             *
             */
            value:'point'
        }

    };

    S.extend(Draggable, Base, {

        _init: function() {
            var self = this,
                node = self.get('node'),
                handlers = self.get('handlers');
            self.set("dragNode", node);

            if (handlers.length == 0) {
                handlers[0] = node;
            }

            for (var i = 0; i < handlers.length; i++) {
                var hl = handlers[i];
                hl = S.one(hl);
                //ie 不能在其內開始選擇區域
                hl.unselectable();
                if (self.get("cursor")) {
                    hl.css('cursor', 'move');
                }
            }
            node.on('mousedown', self._handleMouseDown, self);
        },

        destroy:function() {
            var self = this,
                node = self.get('node'),
                handlers = self.get('handlers');
            for (var i = 0; i < handlers.length; i++) {
                var hl = handlers[i];
                if (hl.css("cursor") == "move") {
                    hl.css("cursor", "auto");
                }
            }
            node.detach('mousedown', self._handleMouseDown, self);
            self.detach();
        },

        _check: function(t) {
            var handlers = this.get('handlers');

            for (var i = 0; i < handlers.length; i++) {
                var hl = handlers[i];
                if (hl.contains(t)
                    ||
                    //子區域內點選也可以啟動
                    hl[0] == t[0]) return true;
            }
            return false;
        },

        /**
         * 滑鼠按下時，檢視觸發源是否是屬於 handler 集合，
         * 儲存當前狀態
         * 通知全局管理器開始作用
         * @param ev
         */
        _handleMouseDown: function(ev) {
            var self = this,
                t = new Node(ev.target);

            if (!self._check(t)) return;
            //chrome 阻止了 flash 點選？？
            //不組織的話chrome會選擇
            //if (!UA.webkit) {
            //firefox 默認會拖動物件地址
            ev.preventDefault();
            //}
            self._prepare(ev);

        },

        _prepare:function(ev) {
            var self = this;

            DDM._start(self);

            var node = self.get("node"),
                mx = ev.pageX,
                my = ev.pageY,
                nxy = node.offset();
            self.startMousePos = self.mousePos = {
                left:mx,
                top:my
            };
            self.startNodePos = nxy;
            self._diff = {
                left:mx - nxy.left,
                top:my - nxy.top
            };
            self.set("diff", self._diff);
        },

        _move: function(ev) {
            var self = this,
                diff = self.get("diff"),
                left = ev.pageX - diff.left,
                top = ev.pageY - diff.top;
            self.mousePos = {
                left:ev.pageX,
                top:ev.pageY
            };
            self.fire("drag", {
                left:left,
                top:top
            });
            DDM.fire("drag", {
                left:left,
                top:top,
                drag:this
            });
        },

        _end: function() {
            this.fire("dragend");
            DDM.fire("dragend", {
                drag:this
            });
        },

        _start: function() {
            this.fire("dragstart");
            DDM.fire("dragstart", {
                drag:this
            });
        }
    });

    return Draggable;

},
{
    requires:["ua","node","base","dd/ddm"]
});
/**
 * droppable for kissy
 * @author:yiminghe@gmail.com
 */
KISSY.add("dd/droppable", function(S, Node, Base, DDM) {

    function Droppable() {
        Droppable.superclass.constructor.apply(this, arguments);
        this._init();
    }

    Droppable.ATTRS = {
        /**
         * 放節點
         */
        node: {
            setter:function(v) {
                if (v) {
                    var n = Node.one(v);
                    n.addClass(DDM.get("prefixCls") + "drop");
                    return n;
                }
            }
        }

    };

    S.extend(Droppable, Base, {
        /**
         * 用於被 droppable-delegate override
         * @param {KISSY.EventObject} ev
         */
        getNodeFromTarget:function(ev) {
            return this.get("node");
        },
        _init:function() {
            DDM._regDrop(this);
        },
        _handleOut:function() {
            var activeDrag = DDM.get("activeDrag");

            this.get("node").removeClass(DDM.get("prefixCls") + "drop-over");
            this.fire("dropexit", {
                drop:this,
                drag:activeDrag
            });
            DDM.fire("dropexit", {
                drop:this,
                drag:activeDrag
            });
            activeDrag.get("node").removeClass(DDM.get("prefixCls") + "drag-over");
            activeDrag.fire("dragexit", {
                drop:this,
                drag:activeDrag
            });
            DDM.fire("dragexit", {
                drop:this,
                drag:activeDrag
            });
        },
        _handleOver:function(ev) {
            var oldDrop = DDM.get("activeDrop");
            DDM.set("activeDrop", this);
            var activeDrag = DDM.get("activeDrag");
            this.get("node").addClass(DDM.get("prefixCls") + "drop-over");
            var evt = S.mix({
                drag:activeDrag,
                drop:this
            }, ev);
            if (this != oldDrop) {
                activeDrag.get("node").addClass(DDM.get("prefixCls") + "drag-over");
                //第一次先觸發 dropenter,dragenter
                activeDrag.fire("dragenter", evt);
                this.fire("dropenter", evt);
                DDM.fire("dragenter", evt);
                DDM.fire("dropenter", evt);
            } else {
                activeDrag.fire("dragover", evt);
                this.fire("dropover", evt);
                DDM.fire("dragover", evt);
                DDM.fire("dropover", evt);
            }
        },
        destroy:function() {
            DDM._unregDrop(this);
        }
    });

    return Droppable;

}, { requires:["node","base","dd/ddm"] });/**
 * generate proxy drag object,
 * @author:yiminghe@gmail.com
 */
KISSY.add("dd/proxy", function(S) {
    function Proxy() {
        Proxy.superclass.constructor.apply(this, arguments);
    }

    Proxy.ATTRS = {
        node:{
            /*
             如何生成替代節點
             @return {KISSY.Node} 替代節點
             */
            value:function(drag) {
                var n = S.one(drag.get("node")[0].cloneNode(true));
                n.attr("id", S.guid("ks-dd-proxy"));
                return n;
            }
        },
        destroyOnEnd:{
            /**
             * 是否每次都生成新節點/拖放完畢是否銷燬當前代理節點
             */
            value:false
        }
    };

    S.extend(Proxy, S.Base, {
        attach:function(drag) {
            var self = this;
            drag.on("dragstart", function() {
                var node = self.get("node");
                var dragNode = drag.get("node");

                if (!self.__proxy && S.isFunction(node)) {
                    node = node(drag);
                    node.addClass("ks-dd-proxy");
                    node.css("position", "absolute");
                    self.__proxy = node;
                }
                dragNode.parent().append(self.__proxy);
                self.__proxy.show();
                self.__proxy.offset(dragNode.offset());
                drag.set("dragNode", dragNode);
                drag.set("node", self.__proxy);
            });
            drag.on("dragend", function() {
                var node = self.__proxy;
                drag.get("dragNode").offset(node.offset());
                node.hide();
                if (self.get("destroyOnEnd")) {
                    node.remove();
                    self.__proxy = null;
                }
                drag.set("node", drag.get("dragNode"));
            });
        },

        destroy:function() {
            var node = this.get("node");
            if (node && !S.isFunction(node)) {
                node.remove();
            }
        }
    });

    return Proxy;
});/**
 * delegate all draggable nodes to one draggable object
 * @author:yiminghe@gmail.com
 */
KISSY.add("dd/draggable-delegate", function(S, DDM, Draggable, DOM) {
    function Delegate() {
        Delegate.superclass.constructor.apply(this, arguments);
    }

    S.extend(Delegate, Draggable, {
        _init:function() {
            var self = this,
                handlers = self.get('handlers'),
                node = self.get('container');
            if (handlers.length == 0) {
                handlers.push(self.get("selector"));
            }
            node.on('mousedown', self._handleMouseDown, self);
        },

        /**
         * 得到適合 handler，從這裡開始啟動拖放，對於 handlers 選擇器字元串陣列
         * @param target
         */
        _getHandler:function(target) {
            var self = this,
                node = this.get("container"),
                handlers = self.get('handlers');

            while (target && target[0] !== node[0]) {
                for (var i = 0; i < handlers.length; i++) {
                    var h = handlers[i];
                    if (DOM.test(target[0], h, node[0])) {
                        return target;
                    }
                }
                target = target.parent();
            }
        },

        /**
         * 找到真正應該移動的節點，對應 selector 屬性選擇器字元串
         * @param h
         */
        _getNode:function(h) {
            var node = this.get("container"),sel = this.get("selector");
            while (h && h[0] != node[0]) {
                if (DOM.test(h[0], sel, node[0])) {
                    return h;
                }
                h = h.parent();
            }
        },

        /**
         * 父容器監聽 mousedown，找到合適的拖動 handlers 以及拖動節點
         *
         * @param ev
         */
        _handleMouseDown:function(ev) {
            var self = this;
            var target = ev.target;
            var handler = target && this._getHandler(target);
            if (!handler) return;
            var node = this._getNode(handler);
            if (!node) return;
            ev.preventDefault();
            self.set("node", node);
            self.set("dragNode", node);
            self._prepare(ev);
        }
    },
    {
        ATTRS:{
            /**
             * 用於委託的父容器
             */
            container:{
                setter:function(v) {
                    return S.one(v);
                }
            },

            /**
             * 實際拖放的節點選擇器，一般用 tag.cls
             */
            selector:{
            }

        /**
         * 繼承來的 handlers : 拖放控制代碼選擇器陣列，一般用 [ tag.cls ]
         * 不設則為 [ selector ]
         *
         * handlers:{
         *  value:[]
         * }
         */
        }
    });

    return Delegate;
}, {
    requires:['./ddm','./draggable','dom']
});/**
 * only one droppable instance for multiple droppable nodes
 * @author:yiminghe@gmail.com
 */
KISSY.add("dd/droppable-delegate", function(S, DDM, Droppable, DOM, Node) {
    function DroppableDelegate() {
        DroppableDelegate.superclass.constructor.apply(this, arguments);
    }

    S.extend(DroppableDelegate, Droppable, {

        /**
         * 根據滑鼠位置得到真正的可放目標，暫時不考慮 mode，只考慮滑鼠
         * @param ev
         */
        getNodeFromTarget:function(ev) {

            var pointer = {
                left:ev.pageX,
                top:ev.pageY
            };

            var container = this.get("container"),
                selector = this.get("selector");

            var allNodes = container.all(selector);

            for (var i = 0; i < allNodes.length; i++) {
                var n = new Node(allNodes[i]);
                if (!n.hasClass("ks-dd-proxy") && DDM.inRegion(DDM.region(n), pointer)) {
                    this.set("lastNode", this.get("node"));
                    this.set("node", n);
                    return n;
                }
            }

            return null;
        },

        _handleOut:function() {
            DroppableDelegate.superclass._handleOut.call(this);
            this.set("node", null);
            this.set("lastNode", null);
        },

        _handleOver:function(ev) {
            var oldDrop = DDM.get("activeDrop");
            DDM.set("activeDrop", this);
            var activeDrag = DDM.get("activeDrag");
            this.get("node").addClass(DDM.get("prefixCls") + "drop-over");
            var evt = S.mix({
                drag:activeDrag,
                drop:this
            }, ev);
            var node = this.get("node"),
                lastNode = this.get("lastNode");

            if (this != oldDrop
                || !lastNode
                || (lastNode && lastNode[0] !== node[0])
                ) {
                /**
                 * 兩個可 drop 節點相鄰，先通知上次的離開
                 */
                if (lastNode) {
                    this.set("node", lastNode);
                    DroppableDelegate.superclass._handleOut.call(this);
                }
                /**
                 * 再通知這次的進入
                 */
                this.set("node", node);
                activeDrag.get("node").addClass(DDM.get("prefixCls") + "drag-over");
                //第一次先觸發 dropenter,dragenter
                activeDrag.fire("dragenter", evt);
                this.fire("dropenter", evt);
                DDM.fire("dragenter", evt);
                DDM.fire("dropenter", evt);
            } else {

                activeDrag.fire("dragover", evt);
                this.fire("dropover", evt);
                DDM.fire("dragover", evt);
                DDM.fire("dropover", evt);
            }
        }
    },
    {
        ATTRS:{
            /**
             * 上一個成為放目標的節點
             */
            lastNode:{
            }
            ,
            /**
             * 放目標節點選擇器
             */
            selector:{
            }
            ,
            /**
             * 放目標所在區域
             */
            container:{
                setter:function(v) {
                    return S.one(v);
                }
            }
        }
    });

    return DroppableDelegate;
}, {
    requires:['./ddm','./droppable','dom','node']
});/**
 * auto scroll for drag object's container
 * @author:yiminghe@gmail.com
 */
KISSY.add("dd/scroll", function(S, Base, Node, DOM) {
    function Scroll() {
        Scroll.superclass.constructor.apply(this, arguments);
    }

    Scroll.ATTRS = {
        node:{
            setter:function(v) {
                return Node.one(v);
            }
        },
        rate:{
            value:[10,10]
        },
        diff:{
            value:[20,20]
        }
    };


    function isWin(node) {
        return !node || node == window;
    }

    S.extend(Scroll, Base, {

        getRegion:function(node) {
            if (isWin(node)) {
                return {
                    width:DOM['viewportWidth'](),
                    height:DOM['viewportHeight']()
                };
            } else {
                return {
                    width:node[0].offsetWidth,
                    height:node[0].offsetHeight
                };
            }
        },

        getOffset:function(node) {
            if (isWin(node)) {
                return {
                    left:DOM.scrollLeft(),
                    top:DOM.scrollTop()
                };
            } else {
                return node.offset();
            }
        },

        getScroll:function(node) {
            if (isWin(node)) {
                return {
                    left:DOM.scrollLeft(),
                    top:DOM.scrollTop()
                };
            } else {
                return {
                    left:node[0].scrollLeft,
                    top:node[0].scrollTop
                };
            }
        },

        setScroll:function(node, r) {
            if (isWin(node)) {
                window.scrollTo(r.left, r.top);
            } else {
                node[0].scrollLeft = r.left;
                node[0].scrollTop = r.top;
            }
        },

        attach:function(drag) {

            var self = this,
                rate = self.get("rate"),
                diff = self.get("diff"),
                event,
                /*
                 目前相對 container 的便宜，container 為 window 時，相對於 viewport
                 */
                dxy,
                timer = null;

            drag.on("drag", function(ev) {
                if (ev.fake) return;
                var node = self.get("node");
                event = ev;
                dxy = S.clone(drag.mousePos);
                var offset = self.getOffset(node);
                dxy.left -= offset.left;
                dxy.top -= offset.top;
                if (!timer) {
                    startScroll();
                }
            });

            drag.on("dragend", function() {
                clearTimeout(timer);
                timer = null;
            });


            function startScroll() {
                //S.log("******* scroll");
                var node = self.get("node"),
                    r = self.getRegion(node),
                    nw = r.width,
                    nh = r.height,
                    scroll = self.getScroll(node),
                    origin = S.clone(scroll);

                var diffY = dxy.top - nh;
                //S.log(diffY);
                var adjust = false;
                if (diffY >= -diff[1]) {
                    scroll.top += rate[1];
                    adjust = true;
                }

                var diffY2 = dxy.top;
                //S.log(diffY2);
                if (diffY2 <= diff[1]) {
                    scroll.top -= rate[1];
                    adjust = true;
                }


                var diffX = dxy.left - nw;
                //S.log(diffX);
                if (diffX >= -diff[0]) {
                    scroll.left += rate[0];
                    adjust = true;
                }

                var diffX2 = dxy.left;
                //S.log(diffX2);
                if (diffX2 <= diff[0]) {
                    scroll.left -= rate[0];
                    adjust = true;
                }

                if (adjust) {

                    self.setScroll(node, scroll);
                    timer = setTimeout(arguments.callee, 100);
                    // 不希望更新相對值，特別對於相對 window 時，相對值如果不真正拖放觸發的 drag，是不變的，
                    // 不會因為程式 scroll 而改變相對值
                    event.fake = true;
                    if (isWin(node)) {
                        // 當使 window 自動滾動時，也要使得拖放物體相對文件位置隨 scroll 改變
                        // 而相對 node 容器時，只需 node 容器滾動，拖動物體相對文件位置不需要改變
                        scroll = self.getScroll(node);
                        event.left += scroll.left - origin.left;
                        event.top += scroll.top - origin.top;
                    }
                    drag.fire("drag", event);
                } else {
                    timer = null;
                }
            }

        }
    });

    return Scroll;
}, {
    requires:['base','node','dom']
});/**
 * dd support for kissy
 * @author: 承玉<yiminghe@gmail.com>
 */
KISSY.add("dd", function(S, DDM, Draggable, Droppable, Proxy, Delegate, DroppableDelegate, Scroll) {
    var dd = {
        Draggable:Draggable,
        Droppable:Droppable,
        DDM:DDM,
        Proxy:Proxy,
        DraggableDelegate:Delegate,
        DroppableDelegate:DroppableDelegate,
        Scroll:Scroll
    };

    S.mix(S, dd);

    return dd;
}, {
    requires:["dd/ddm",
        "dd/draggable",
        "dd/droppable",
        "dd/proxy",
        "dd/draggable-delegate",
        "dd/droppable-delegate",
        "dd/scroll"]
});
