/****************************************************
 * 												    *		
 * 			Sociax HTML 標籤關聯模型  			*
 *                                                  *
 ****************************************************/

/**
 * HTML 標籤關聯模型
 * @model-node 模型節點的標籤屬性標記
 * @event-node 模型下事件節點的標籤屬性標記
 * @author _慢節奏
 */
(function(window) {

var document = window.document;

/**
 * 啟用模型
 * 
 * @param node 元素節點
 * @param node 父模型節點，若為空則將node 作為父模型節點
 * @param fns  掛載到標簽上的事件方法，格式說明如下：
 * {
 *     model: {
 *         method1 : {
 *         	   click     : function(){},
 *         	   mouseover : function(){},
 *        	   mouseout  : function(){},
 *         	   load      : function(){}
 *     	   },
 *     	   method2 : {
 *             blur   : function(){},
 *             focus  : function(){},
 *             submit : function(){}
 *     	   }
 *     },
 *     event: {
 *         method1 : {
 *         	   click     : function(){},
 *         	   mouseover : function(){},
 *        	   mouseout  : function(){},
 *         	   load      : function(){}
 *     	   },
 *     	   method2 : {
 *             blur   : function(){},
 *             focus  : function(){},
 *             submit : function(){}
 *     	   }
 *     }
 * }
 */
var module = function( node, fns ) {
    module.addFns(fns);
	if ( node ) {
		// 預清除，防止重複模型化引起的雙重快取
		module.nodes.init( node );
	}
};

/**
 * 儲存事件的方法
 * 
 * @param fns  掛載到標簽上的事件方法，格式說明同module 函數的fns 參數
 */
module.addFns = function( fns ) {
	if ( !fns ) return module;
	if ( fns.model ) {
		module.addModelFns( fns.model );
	}
	if ( fns.event ) {
		module.addEventFns( fns.event );
	}
	return module;
};

/**
 * 儲存模型事件的方法
 * 
 * @param fns  掛載到模型上的事件方法，格式說明如下：
 * {
 *     method1 : {
 *         click     : function(){},
 *         mouseover : function(){},
 *         mouseout  : function(){},
 *         load      : function(){}
 *     },
 *     method2 : {
 *         blur   : function(){},
 *         focus  : function(){},
 *         submit : function(){}
 *     }
 * } 
 */
module.addModelFns = function( fns ) {
	if ( "object" != typeof fns ) return module;
	var name;
	for ( name in fns ) {
		module.nodes.models.fns[name] = fns[name];
	}
	return module;
};

/**
 * 儲存模型下事件節點的方法
 * 
 * @param fns  掛載到模型上的事件方法，屬性說明同module.addModelFns 的fns 參數
 */
module.addEventFns = function( fns ) {
	if ( "object" != typeof fns ) return module;
	var name;
	for ( name in fns ) {
		module.nodes.events.fns[name] = fns[name];
	}
	return module;
};

/**
 * 獲取節點的參數
 *
 * @param node 模型/事件節點
 */
module.getArgs = function( node ) {
	return node.getAttribute( "model-node" ) ? module.getModelArgs( node ) : module.getEventArgs( node );
};

/**
 * 設定節點的參數
 *
 * @param node 模型/事件節點
 * @param uri URI 格式的參數
 */
module.setArgs = function( node, uri ) {
	return node.getAttribute( "model-node" ) ? module.setModelArgs( node, uri ) : module.setEventArgs( node, uri );
};

/**
 * 獲取模型節點的參數
 *
 * @param node 模型節點
 */
module.getModelArgs = function( node, uri ) {
    node.args || ( node.args = module.URI2Obj( node.getAttribute( "model-args" ) ) );
    return node.args;
};

/**
 * 設定模型節點的參數
 *
 * @param node 模型節點
 */
module.setModelArgs = function( node, uri ) {
    node.args = undefined;
    node.setAttribute( "model-args", uri );
    return module;
};

/**
 * 獲取事件節點的參數
 *
 * @param node 事件節點
 */
module.getEventArgs = function( node ) {
    node.args || ( node.args = module.URI2Obj( node.getAttribute( "event-args" ) ) );
    return node.args;
};

/**
 * 設定事件節點的參數
 *
 * @param node 事件節點
 */
module.setEventArgs = function( node, uri ) {
    node.args = undefined;
    node.setAttribute( "event-args", uri );
    return module;
};

/**
 * 將uri轉換為物件格式
 *
 * @param uri URI 格式的資料
 */
module.URI2Obj = function( uri ) {
	if ( ! uri ) return {};
    var obj = {},
    	args = uri.split( "&" ),
    	l, arg;
    l = args.length;
    while ( l -- > 0 ) {
        arg = args[l];
        if ( ! arg ) {
            continue;
        }
        arg = arg.split( "=" );
        obj[arg[0]] = arg[1];
    }
    return obj;
};

/**
 * 獲取全局內指定的模型節點
 *
 * @param name 模型節點的命名
 */
module.getModels = function( name ) {
	return module.nodes.models[name];
};

/**
 * 獲取全局內指定的事件節點
 *
 * @param name 事件節點的命名
 */
module.getEvents = function( name ) {
	return module.nodes.events[name];
}

/**
 * 刪除節點上的監聽
 *
 * @param object node 節點物件
 */
module.removeListener = function( node ) {
	module.nodes.removeListener( node );
	return module;
};

/**
 * 為節點添加監聽
 *
 * @param object node 節點物件
 * @param object events 監聽的事件
 * {
 *     click     : function(){},
 *     mouseover : function(){},
 *     mouseout  : function(){}
 * }
 */
module.addListener = function( node, events ) {
	module.nodes.addListener( node, events );
	return module;
};

module.getPreviousModel = function( node, siblingName ) {
	return module.nodes.getPreviousModel( node, siblingName );
};

module.getNextModel = function( node, siblingName ) {
	return module.nodes.getNextModel( node, siblingName );
};

module.getPreviousEvent = function( node, siblingName ) {
	return module.nodes.getPreviousEvent( node, siblingName );
};

module.getNextEvent = function( node, siblingName ) {
	return module.nodes.getNextEvent( node, siblingName );
};

/**
 * 模型化節點物件
 * 
 * @property function init 初始化模型
 * @property function _init 逐級掃描指定節點下的各級子元素的模型結構，並快取模型和事件的DOM物件
 * @property function clear 清楚元素節點的子模型節點和子事件節點合集物件
 * @property function getParentModel
 * @property function addListener 為模型和事件節點附加事件方法
 * @property object _onload 自定義onload 事件
 * @property object _onload.execute 執行onload 事件佇列
 * @property object _onload.queue onload 事件佇列
 * @property object models 羅列並快取模型節點
 * @property object events 羅列並快取事件節點
 * @property object models.fns 存放模型節點的事件方法
 * @property object events.fns 存放事件節點的事件方法
 */
module.nodes = {
	init: function( node ) {
		// 初始化模型
		this._init( node );
		// 執行onload 事件
		this._onload.execute();
		return this;
	},
	_init: function( node, parentModel ) {
		var childNode = node.firstChild,
			childParentModel,
		    model_name,
		    event_name;

		! parentModel && ( parentModel = this.getParentModel( node ) );

		switch ( node.nodeName ) {
			case "DIV": case "UL":case "DL": 
			case "FORM":case "LI":case "DD": 
				model_name = node.getAttribute( "model-node" );
				if ( model_name ) {
					this._clearModel( node );

					node.modelName = model_name;

					this.addListener( node, this.models.fns[model_name] );

					node.parentModel = parentModel;

					( parentModel.childModels[model_name] = parentModel.childModels[model_name] || [] ).push( node );

					( this.models[model_name] = this.models[model_name] || [] ).push( node );

					childParentModel = node;
				}
				break;
			case "A": case "SPAN": case "LABEL":
			case "STRONG": case "INPUT": case "SELECT":
			case "BUTTON": case "IMG": case "TEXTAREA": 
			case "H1": case "H2": case "H3": case "H4":case "I":			
				event_name = node.getAttribute( "event-node" );
				if ( event_name ) {
					this._clearEvent(node);

					node.eventName = event_name;

					this.addListener( node, this.events.fns[event_name] );

					node.parentModel = parentModel;
					( parentModel.childEvents[event_name] = parentModel.childEvents[event_name] || [] ).push( node );

					( this.events[event_name] = this.events[event_name] || [] ).push( node );
				}
				break;
			case "HEAD": case "BODY":
				this[node.nodeName.toLowerCase()] = node;
				break;
			case "#document":
				this._clearModel(node);
				break;
		}

		! childParentModel && ( childParentModel = parentModel );
		while ( childNode ) {
			(1 == childNode.nodeType ) && this._init( childNode, childParentModel );
			childNode = childNode.nextSibling;
		}
	},
	_clearModel: function( node ) {
		node.modelName   = undefined;
		node.parentModel = undefined;
		node.childModels = {};
		node.childEvents = {};
		node.args = undefined;
		return this;
	},
	_clearEvent: function( node ) {
		node.eventName   = undefined;
		node.parentModel = undefined;
		node.args = undefined;
		return this;
	},
	getParentModel: function( node ) {
		var parentNode = node.parentNode,
			parentModel;
		if ( parentNode && 1 === parentNode.nodeType ) {
			parentModel = parentNode.getAttribute('model-node') ? parentNode : this.getParentModel( parentNode );
		}
		return parentModel || document;
	},
	getPreviousModel: function( node, siblingName ) {
		return this._getSiblingNode( node, { siblingType: "model", siblingName: siblingName }, "previous" );
	},
	getNextModel: function( node, siblingName ) {
		return this._getSiblingNode( node, { siblingType: "model", siblingName: siblingName }, "next" );
	},
	getPreviousEvent: function( node, siblingName ) {
		return this._getSiblingNode( node, { siblingType: "event", siblingName: siblingName }, "previous" );
	},
	getNextEvent: function( node, siblingName ) {
		return this._getSiblingNode( node, { siblingType: "event", siblingName: siblingName }, "next" );
	},
	_getSiblingNode: function( node, siblingArgs, direction ) {
		var sibling;
		if ( !node ) return null;
		sibling = node[ [ direction, "Sibling" ].join("") ];
		return ( sibling && ( siblingArgs.siblingName === sibling[ [ siblingArgs.siblingType, "Name" ].join("") ] ) ) ? sibling : this._getSiblingNode( sibling, siblingArgs, direction );
	},
	addListener: function( node, events ) {
        if ( "object" == typeof events ) {
        	var event;
            for ( event in events ) {
            	switch ( event ) {
            		case "load":
            			node[event] = events[event];
                        // 添加到佇列
                        this._onload.queue.push( node );
           			    break;
            		case "callback":
            			node[event] = events[event];
            			break;
           			case "mouseenter": case "mouseleave":
           				// 相容非IE
           				if ( document.addEventListener ) {
           					var refer = {mouseenter: "mouseover", mouseleave: "mouseout"};
           					node["on" + refer[event]] = (function( event, fn ){
           						return function( e ) {
	           						// 上一響應mouseover/mouseout 事件的元素
	           						var parent = e.relatedTarget;
	           						// 假如存在這個元素並且這個元素不等於目標元素
									while( parent && parent != this ){
										try {
											//上一響應的元素開始往上尋找目標元素
											parent = parent.parentNode;
										} catch( e ) {
											break;
										}
							 
									}
									// 假如找不到，表明當前事件觸發點不在目標元素內
									if ( parent != this ) {
										//運行目標方法，否則不運行
										node[event] = fn;
										node[event]();
									}
								};
           					})( event, events[event] );
           				} else {
                        	node["on" + event] = events[event];
           				}
           				break;
            		default :
                        node["on" + event] = events[event];
            	}
            }
        }
	},
	removeListener: function( node ) {
		node.onclick = node.onfocus = node.onblur = node.onmouseout
		= node.onmouseover = node.onmouseenter = node.onmouserleave
		= node.onchange = null;
		return this;
	},
	_onload: {
		execute: function() {
			var l = this.queue.length,
				i;
			for ( i = 0; i < l; i ++ ) {
				
				this.queue[i]["load"]();
				this.queue[i]["load"] = undefined;	
				
			}
			// 重置佇列
			this.queue = [];
		},
		queue: []
	},
	models: {
		fns: {

		}
	},
	events: {
		fns: {
		}
	},
	getHead: function() {
		this.head || (this.head = document.getElementsByTagName("head")[0]);
		return this.head;
	},
	getBody: function() {
		this.body || (this.body = document.getElementsByTagName("body")[0]);
		return this.body;
	}
};

/**
 * 載入CSS 檔案
 *
 * @param string url CSS 檔案URL
 */
module.getCSS = (function() {
	var temp = [];
	//返回內部包函數,供外部呼叫並可以更改temp的值
	return function( url ){
		var	head = module.nodes.getHead(),
			flag = 0,
			link,
			i = temp.length - 1;
		// 第二次呼叫的時候就不=0了
		for ( ; i >= 0; i -- ) {
			flag = ( temp[i] == url ) ? 1 : 0;
		}

		if ( flag == 0 ) {
			// 未載入過
		    link  = document.createElement( "link" );
			link.setAttribute( "rel", "stylesheet" );
			link.setAttribute( "type", "text/css" );
			link.setAttribute( "href", url );
			head.appendChild( link );
			temp.push( url );
		}
	};
})();

/**
 * 載入js 檔案
 *
 * @param string url js 檔案URL
 * @param function fn 執行函數
 */
module.getJS = (function() {
	var temp = [];
	//返回內部包函數,供外部呼叫並可以更改temp的值
	return function( url, fn ){
		// 第二次呼叫的時候就不=0了
		var	head,
			script,
			onload,
			flag = 0,
			i = temp.length - 1;
		// 第二次呼叫的時候就不=0了
		for ( ; i >= 0; i -- ) {
			flag = ( temp[i] == url ) ? 1 : 0;
		}

		if ( flag == 0 ) {
			// 未載入過
			// 記錄url
			temp.push( url );
			// 載入
			head = module.nodes.getHead();
			script = document.createElement( "script" );
			script.setAttribute( "src", url );

			if ( "function" == typeof fn ) {
				script.onload = script.onreadystatechange = function() {
					// FF 下沒有readyState 值，IE 有readyState 值，需加以判斷
					if( ! this.readyState || "loaded" == this.readyState || "complete" == this.readyState ) {
						this.onload = this.onreadystatechange = null;

						fn();

						fn = undefined;

						script = undefined;
					}
				};
			}

			head.appendChild( script );
		} else {
			if("function" == typeof fn){
				fn();
				fn = undefined;
			}	
		}
	};
})();

/**
 * Execute functions when the DOM is ready 
 *
 * @param function fn 格式的資料
 */
module.ready = function( fn ) {
	if ( "function" !== typeof fn ) {
		return;
	}

	if ( document.addEventListener ) {
		// Use the handy event callback
		document.addEventListener( "DOMContentLoaded", fn, false );
	// If IE event model is used
	} else if ( document.attachEvent ) {
		// maybe late but safe also for iframes
		document.attachEvent( "onreadystatechange", fn );
	}
};

window.M = module;

M.ready(function() {
	M(document);
});

})(window);