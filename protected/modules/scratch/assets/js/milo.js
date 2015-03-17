/**
 * @author cathzhang 
 * @version 0.1.0.0 
 * @date 2011-05-12 
 * @class milo.base 
 * <p>
 * ��������Ҫ������namespace��extend��ط�����is�ж϶���ϵ��<br/>
 * ���������з������󶨵�window�����У���ֱ�ӶԷ��������е��á�<br/>
 * </p>
 * <p>
 * Example code:
 * <pre><code>
var a;
console.log(isUndefined(a));    //true
var b= new Array(1,2);
console.log(isUndefined(b));    //false
console.log(isUndefined(b[4])); //true
 *</code></pre>
 * </p>
 * <p>
 * �������ࣺ
 * <pre><code>
var cal1 = cloneClass(Calendar);
var cal2 = cloneClass(Calendar);
 * </code></pre>
 * </p>
 */
 
/**
 * ���������ռ�
 * @param {string} �ռ����ƣ��ɶ�� 
 * @return {object} ����
 */	 
namespace = function(){
    var argus = arguments;
    for(var i = 0; i < argus.length; i++){
        var objs = argus[i].split(".");
		var obj = window;
        for(var j = 0; j < objs.length; j++){
            obj[objs[j]] = obj[objs[j]] || {};
            obj = obj[objs[j]];
        }
    }
    return obj;
};

namespace("milo.base");

(function(){
	/**
	 * Ϊ���������չ���Ժͷ���
	 * @param {object} object ����
	 * @return {bool} ��/��
	 */	 
	milo.base.extend = function(destination, source) {
		if (destination == null) {
			destination = source
		}
		else {
			for (var property in source){		
				if ( getParamType(source[property]).toLowerCase() === "object" && 
					getParamType(destination[property]).toLowerCase() === "object" )
						extend(destination[property], source[property])
				else
					destination[property] = source[property];
			}
		}
		return destination;
	}
	
	milo.base.extendLess = function(destination, source) {
		var newopt = source;
		for (var i in destination) {
			if (isObject(source) && typeof(source[i]) != 'undefined') {
				destination[i] = newopt[i]
			}
		}
		return destination
	}
	
	/**
	 * ԭ�ͼ̳���
	 * @param {object} object ����
	 * @return {object} ���ɵ�����
	 */	 
	milo.base.cloneClass = function(object){		
		if(!isObject(object)) return object;
		if(object == null) return object;
		var F = new Object();
		for(var i in object)
			F[i] = cloneClass(object[i]);
		return F; 		
	}

	milo.base.extend( milo.base, {
		/**
		 * �ж϶����Ƿ���
		 * ��ʵֻ�Զ����е�Ԫ���ж���Ч�����Ǵ��������˷������޷����ã���Ҫ�����try
		 * @param {object} object ����
		 * @return {bool} ��/��
		 */
		isUndefined : function(o){ 
    		 	return o === undefined && typeof o == "undefined";
    	},
		/**
		 * �ж϶����Ƿ�����
		 * @param {object} object ����
		 * @return {bool} ��/��
		 */
		isArray : function(obj) {
			return getParamType(obj).toLowerCase() === "array";
		},		
		/**
		 * �ж϶����Ƿ���
		 * @param {object} object ����
		 * @return {bool} ��/��
		 */
		isFunction : function(obj){
			return getParamType(obj).toLowerCase() === "function";
		},		
/**
		 * �ж϶����Ƿ���ֵ
		 * @param {object} object ����
		 * @return {bool} ��/��
		 */
		isNumber : function(obj) {
			return getParamType(obj).toLowerCase() === "number";
		},		
		/**
		 * �ж϶����Ƿ����
		 * @param {object} object ����
		 * @return {bool} ��/��
		 */
		isObject : function(obj) {
			return getParamType(obj).toLowerCase() === "object";
		},
		/**
		 * �ж϶����Ƿ��ַ���
		 * @param {object} object ����
		 * @return {bool} ��/��
		 */
		isString : function(obj) {
			return getParamType(obj).toLowerCase() === "string";
		},		
		/**
		 * �ж϶����Ƿ�DOMԪ��
		 * @param {object} obj DOM����
		 * @return {bool} ��/��
		 */
		isDom : function(obj){
    		try{
    			return typeof obj === "object" && obj.nodeType==1 && typeof obj.nodeName == "string";
    		}
    		catch(e){
    			//console.log(e)
    			return false;
    		}
    	}, 
    	toType : function(obj) {
     		return Object.prototype.toString.call(obj).match(/\s([a-z|A-Z]+)/)[1].toLowerCase();
    	},
    	/**
		 * �ж��Ƿ񲼶�ֵ
		 * @param {object} object ����
		 * @return {bool} ��/��
		 */
		isBoolean : function(obj) {
			return getParamType(obj).toLowerCase() === "boolean";
		},
		/**
		 * �ж϶����Ƿ�����
		 * @param {object} object ����
		 * @return {bool} ��/��
		 */
		isDate : function(obj){
			return getParamType(obj).toLowerCase() === "date";
		},
    	
    	
    	/**
		 * ��ȡdom����
		 * @param {string|dom} dom��id,class,tagname
		 * @return {dom} 
		 */
		g : function(obj){
		/*	var elements;
			if (!isString(obj)) return false;
			var CLASS_SELECTOR, ID_SELECTOR, TAG_SELECTOR;
		    CLASS_SELECTOR = /^\.([\w-]+)$/;
		    ID_SELECTOR = /^#[\w\d-]+$/;
		    TAG_SELECTOR = /^[\w-]+$/;

		    if (CLASS_SELECTOR.test(selector)) {
		        elements = document.getElementsByClassName(selector.replace(".", ""));
		    } else if (TAG_SELECTOR.test(selector)) {
		        elements = document.getElementsByTagName(selector);
		    } else if (ID_SELECTOR.test(selector) && document === document) {
		        elements = document.getElementById(selector.replace("#", ""));
		        if (!elements) {
		          elements = [];
		        }
		    } else {
		        elements = document.querySelectorAll(selector);
		    }
			return elements;*/
            return (typeof obj=='object')?obj:document.getElementById(obj);
		},
		
		query : function(domain,selector){
			if (!isDom(domain) || !isString(selector))	return false;
			var CLASS_SELECTOR, ID_SELECTOR, TAG_SELECTOR;
		    CLASS_SELECTOR = /^\.([\w-]+)$/;
		    ID_SELECTOR = /^#[\w\d-]+$/;
		    TAG_SELECTOR = /^[\w-]+$/;		
      		var elements;
	
	       selector = selector.trim();
	       if (CLASS_SELECTOR.test(selector)) {
	        elements = domain.getElementsByClassName(selector.replace(".", ""));
	       } else if (TAG_SELECTOR.test(selector)) {
	        elements = domain.getElementsByTagName(selector);
	       } else if (ID_SELECTOR.test(selector) && domain === document) {
	        elements = domain.getElementById(selector.replace("#", ""));
	        if (!elements) {
	          elements = [];
	        }
	      } else {
	        elements = domain.querySelectorAll(selector);
	      }
	      if (elements.nodeType) {
	        return [elements];
	      } else {
	        return Array.prototype.slice.call(elements);
	      }
		},
		
		querySel : function(sel){
			return document.querySelector(sel)
		},
		
		querySelAll : function(sel){
			return document.querySelectorAll(sel)
		}
		
	});
	

	
	/**
	 * ��ȡ��������
	 * @private
	 * @param {object} object ����
	 * @return {string} ����
	 * ���ж����ͣ�Boolean Number String Function Array Date RegExp Object
	 */	
	function getParamType(obj){
		return obj == null ? String(obj) : 
			Object.prototype.toString.call(obj).replace(/\[object\s+(\w+)\]/i,"$1") || "object";
	}
	
})();

milo.base.extend(window, milo.base);
milo.g = g;
/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2011-11-21
 * @class milo.config
 * ͨ����������
 */
 
namespace("milo.config");

(function(){	
	var config = {
		loaderPath : "http://ossweb-img.qq.com/images/js/mobile/",
		version : "20131128",
		expires : 30000
	}
	extend(milo.config, config);
})(); 
namespace("milo.browser");

(function(browser){	
	
	browser.version= false; //����ϵͳ�İ汾
	browser.bVersion= false;//����webkit������İ汾
    browser.ua = navigator.userAgent;
    
	browser.android = function(){
		var regular_result = browser.ua.match(/(Android)\s+([\d.]+)/),
			os_boolean = !!regular_result;
		if(!browser.version && os_boolean){
			browser.version = regular_result[2];
		}
		browser.android = function(){return os_boolean;};
		return os_boolean;
	}
	
	browser.ios = function(){
		var regular_result = browser.ua.match(/.*OS\s([\d_]+)/),
			os_boolean = !!regular_result;
		if(!browser.version && os_boolean){
			browser.version = regular_result[1].replace(/_/g, '.');
		}
		this.ios = function(){return os_boolean;};
		return os_boolean;
	}	

	browser.ipod = function() {
		var regular_result = browser.ua.match(/(iPod).*OS\s([\d_]+)/),
			os_boolean = !!regular_result;
		if(!browser.version && os_boolean){
			browser.version = regular_result[2].replace(/_/g, '.');
		}
		this.ipod = function(){return os_boolean;};
		return os_boolean;
	}
	
	browser.ipad= function() {
		var regular_result = browser.ua.match(/(iPad).*OS\s([\d_]+)/),
			os_boolean = !!regular_result; 
		if(!browser.version && os_boolean){
			browser.version = regular_result[2].replace(/_/g, '.');
		}
		this.ipad = function(){return os_boolean;};
		return os_boolean;
	}
	
	browser.iphone= function() {

		var regular_result = browser.ua.match(/(iPhone);.*OS\s([\d_]+)/),
			os_boolean = !!regular_result;
		if(!browser.version && os_boolean){
			browser.version = regular_result[2].replace(/_/g, '.');
		}
		this.iphone = function(){return os_boolean;};
		return os_boolean;
	}
	
	browser.webkit= function() {
		var regular_result = browser.ua.match(/WebKit\/([\d.]+)/),
			os_boolean = !!regular_result;
		if(!browser.version && os_boolean){
			browser.bVersion = regular_result[1];
		}
		this.webkit = function(){return os_boolean;};
		return os_boolean;
	}
	
	browser.uc= function() {
		var regular_result = browser.ua.match(/UC/),
			os_boolean = !!regular_result;
		browser.uc = function(){return os_boolean;};
		return os_boolean;
	}
	
	browser.safari =  function() {
		var regular_result = browser.ua.match(/Version.*Safari/),
			os_boolean = !!regular_result;
		browser.safari = function(){return os_boolean;};
		return os_boolean;
	}
	/**
	* �÷�������ȡ���ĵ�����ĸ߿�
	* @return �ĵ�����ĸ߿�
	*/	
	browser.screen = function(){
      return {
        width: window.innerWidth,
        height: window.innerHeight
      };	
	}
	
	/**
	* �÷�������ȡ�ÿ�������Ŀ�
	* @return ��������ĸ߶�
	*/		
	browser.wh = function(){
		return document.documentElement.clientHeight;
	}

	/**
	* �÷�������ȡ�ÿ�������Ŀ�
	* @return ��������Ŀ�
	*/		
	browser.ww = function(){
		return document.documentElement.clientWidth;
	}
	
	/**
	* �÷��������жϵ�ǰ״̬ʱ������������
	* @return ���Ϊ��������true ���򷵻�false
	*/	
	browser.hv= function() {
		if(browser.wh()/browser.ww()>1){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	* �÷���������ios�����ص�����
	*/
	browser.hideUrl= function() {
		setTimeout(function() {
                window.scrollTo(0, 1);
		},200);
	};	
})(milo.browser);

/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2011-05-12  
 * @class milo.loader
 * @demo http://gameact.qq.com/milo/core/load.html
 * @extends milo.base
 * <br/>
 * �������js/css�Ļ���ģ��<br/>
 * ���������з������󶨵�window�����У���ֱ�ӶԷ��������е��á�<br/>
 * modified by cathzhang on 2011-08-11 <br/>
 * modified content �Ż���loadscript����ʹ֮�ɱ���������<br/>
 * ajax����������δ������ݲ�����<br/>
 * includer�����parent��dep�������ǿ���̫˳�ۣ�modified on 2011-10-10 �����հ���<br/>
 * modified by cathzhang on 2011-10-11 <br/>
 * modified content ����ģ�黯���÷��� <br/> 
 * <p>
 * ����person��:
 * <pre><code>
need(["person"], function(person){
	alert("name:" + person.name);
});
 *</code></pre>
 * </p>
 * <p>
 * person��Ķ��壺(ֱ�ӷ�����������)
 * <pre><code>
define({
	name : 'angel',
	age : 1000
});
 * </code></pre>
 * </p>
 * <p>
 * person��Ķ�������д����
 * <pre><code>
define('person',[], function(){
	return {
		name : 'angel',
		age : 1000
	}
});
 * </code></pre>
 * </p>
 * <p>
 * ����ģ������������ʱ��
 * <pre><code>
define(["animal"],function(animal) {
        return {
			paw : animal.paw,
			play : function() {
				animal.play();
                return "miao";
            },
            eat: function() {
                console.log("fish");
            }
        }
    }
);
 * </code></pre>
 * </p>
 * <p>
 * ��ģ���·���ᶨ�嵽��<b>http://ossweb-img.qq.com/images/js/milo/biz</b>
 * </p> 
 */

 
namespace("milo.loader");


(function(loader){	
	var __loading = null,
		loaded = {}, //�Ƿ�����
		loading = {}, //��������  ����ǰ�ͽ������Դ���ͬʱ������󣬵��˴�д�ˣ��ͶԲ�סloaded�ˣ����ع�
		queue = [];  //define��deps���� 
		//modulemap = {};//���ض���	
		
	// add by dickma
	loader.modulemap={} ; //ȫ�ֵ�Module���� 
	loader.defineMap=[];
	loader.isMainLoaded=0; //Main�Ƿ��Ѿ����صı�ʶ��
	
	
    //Define��Map���ģ�����ݣ�����ԤNeed by dickma

	loader.preNeed=function(){
		loader.need(loader.defineMap,null);
	}
	
	
	/**
	 * ���ض��󷽷� ��Ӧģ������define�������壬���򷵻����޷�ʹ��
	 * @param {array} modules ģ������ 
	 * @param {function} callback �ص� �ص������еĲ���Ϊ����ģ��ķ���
	 * @return {undefined} undefined 
	 */
	loader.need = function(modules,callback){
		//�������������жϣ��ɻص�ʱ�Զ���Ӧ�������undefined����ȱ���޷�ʹ��
		//**��callback����function�򲻴����򲻽��лص�
		//**modulesֻ�������жϡ�
		if (!isArray(modules) ) { 
			modules = new Array(modules)
        }
		
		var mc = moduleContainer("", modules, callback);
		start(mc);
		//**�����Ƿ������м���ɲ㣬������ͨ���Լ�����ֵ��
		//**��һ�ڿ�����չ����������return�õ�һ��������ֱ��ʹ�ö���ķ�����talk��
		
		return undefined;
	}
	
	/**
	 * ģ�鶨�巽��
	 * @param {string} name ���ض���
	 * @param {array} modules ���ض���
	 * @param {function} callback ���ض���
	 * @return {undefined} undefined 
	 */
	loader.define = function(name,deps,callback){
		//��name����ʱ���ļ���ȡname��urlcb�ص��д���
		//��deps����[]		
		if (!isString(name)){
			callback = deps;
			deps = name;
			//add by dickma@2015.01.24	
			//name = null;			
			name = "noname_"+(Math.floor(Math.random()*1000000));	
			
		}
		
		if (!isArray(deps)){
			callback = deps;
			deps = [];
			name = "noname_"+(Math.floor(Math.random()*1000000));	
		}	

		//**callback��function Ϊobjectʱ��ֱ��Ϊname����object��cb�ص��У�
		
		queue.push([name, deps, callback]);
		
		loader.defineMap.push(name);  //add by dickma
		
		return undefined;
	}
	
	//jquery֧��
	
	
	loader.define.amd = {
        //multiversion: true,
        //plugins: true,
        jQuery: true
    };
	
	
	
	/**
	 * �����ļ�����(����ļ�)
	 * �ʺϽ��п�������
	 * @public
	 * @param {array} filepaths ��Ҫ���ؽű�
	 * @param {function} callback �ص������д���һ�������������Ƿ�ɹ���
	 * @return {undefined} ��
	 */
	loader.include = function(filepaths, callback){
		var files = new Array();
		files = files.concat(filepaths);
		if (!isFunction(callback)) {callback = function(){}}
		var ic = includerContainer(files, callback);
		start(ic);
	}
	
	/**
	 * ���ؽű�������������һ�ļ���
	 * @param {string} url ·�� url·�������κ���֤
	 * @param {function} callback �ص�����  �������������Ƿ���سɹ�
	 * @return {undefined} undefined 
	 */
	loader.loadScript = function(url, callback){
		if (!isFunction(callback)) callback = function(){};
		loadScript(url, callback);
	}
	
	/**
	 * ����CSS������ʽ 
	 * @param {string} url ·�� url����http��ͷ����ģ������ͬ�������path��css���粻��css��β���������
	 * @param {function} callback �ص�����  �������������Ƿ���سɹ�
	 * @return {undefined} undefined 
	 */
	loader.loadCSS = function(url, callback){
		if (url.search(/^http:\/\//i) == -1){
			url = milo.config.loaderPath + url.replace(/\./g, "/") + ".css"
					 + "?" + milo.config.version; 
		}
		var isCSS = /\.css(\?|$)/i.test(url);		
		if (!isFunction(callback)) callback = function(){};
		if (isCSS & !loaded[url]) {
			loadCSS(url, callback);
			loaded[url] = true;
		}
	}
	
	/******************************************************/
	/*****************need��define����*********************/
	/******************************************************/
	
	/**
	 * ���ݼ�����
	 * @private
	 * @param {string} name ��������
	 * @param {array} modules ����ģ�����
	 * @param {function} callback �ص�����
	 * @return {object} object 
	 * ÿһ���������Ҫ���ص��ļ�������һ��������
	 */
	function moduleContainer(name, modules, callback){	
		var needown = 0,
			hasdown = 0,
			hasmaped = 0,
			need = {};
			
		
		for(var i=0 ; i < modules.length; i++){
			var url = getModulePath(modules[i]);
			needown ++;
			//�����ع���ģ����д�������������
			//��������maped������ɻص�ͳһ����		

            //modify by dickma
			if (modules[i]=="util.zepto" && typeof(Zepto)!=="undefined"){
				//modulemap["util.zepto"]=Zepto;
				milo.loader.modulemap["util.zepto"]=Zepto;
				hasdown ++;
			   continue;
			
			}else{	
				if (loaded[modules[i]] || loading[modules[i]]) {
					hasdown ++;
					continue;
				}
				need[modules[i]] = url;		
			}
				
		}

		return {
			name : name, //ģ����
			modules : modules, //����ģ����
			need : need,   //������������(����load����)
			res : new Array(), //���������� �����
			//**���ڶ����ʱ�䴦����Ҫ����
			expires : (modules.length) * milo.config.expires, //����ʱ��
			callback : callback, //ģ�������ɺ�Ļص�
			needown : needown,  //��Ҫ������
			hasdown : hasdown,	//��������
			hasmaped : hasmaped, //�ѳɹ�������

			/**
			 * ���ļ����سɹ���ص�
			 * @param {bool} ret �������
			 * @param {string} name ģ������
			 * @return {undefined} undefined 
			 * ��ȡ�ļ��ڵ�define���󣬴�����mc
			 * ���� startPos ��������ʼȡ��λ�á��ṩ�Ծ����������Ҵ���ļ���֧�֣� by Dickma at 2014.01.27  
			 */
			loadUrlCallback : function(ret, name,startPos){
				//�����Ƿ�ɹ����������������������Ѵ���
				this.hasdown ++;	
				//console.log("loadUrlCallback:"+name+" -> "+ret);
				if(ret){					
					loaded[name] = true;
					
					//modify by dickma
					if (!startPos){
					   startPos=0;
					}
					
					var deps = queue.splice(startPos,1).pop();
					//console.log("######################");
					//console.log(name);
					//console.log(queue);
					//console.log(modulemap);
					if (deps==null) {
						milo.loader.modulemap[name] = ret;
						return;
					}
					
					
					
					
					//**��deps������name���ֲ�һ��ʱ��������ô��������ô����
					//if (deps[0] == null){
						deps[0] = name;
					//}
					//ÿ�½�һ��deps����					
					var mc = moduleContainer.apply(null,deps);
					start(mc);					
				}
				else{
					//ʧ����ǰ������	
					//this.res[name] = "undefined"; 	
					milo.loader.modulemap[name] = "undefined"; 	
				}
			},		
			
			/**
			 * mc�����ļ����سɹ���ص�
			 * @param {bool} ret �������
			 * @param {string} name ģ������
			 * @return {undefined} undefined 
			 * �ȴ�maped�ɹ���
			 */
			loadInluderCallback : function(ret){		
				if (!ret){
					//**��ʧ���Ƿ����ǰ������
					//ʧ�ܴ���	
					//��ģ����δ����ģ����Ϊundefined
					//����maped����					
				}				
				this.checkMaped();
			},
			
			/**
			 * mc�����ļ����سɹ���ص�
			 * @param {bool} ret �������
			 * @param {string} name ģ������
			 * @return {undefined} undefined 
			 * �ȴ�maped�ɹ���
			 */
			completeLoad : function(maped){	
				var ret = [];
				//**ȡcontent��deps��Ӧ��modulemap�������
				for(var i=0 ; i < this.modules.length; i++){
					ret.push(this.res[this.modules[i]]);
				}
				
				if (!isFunction(this.callback) && !isObject(this.callback)) return false;
				if (this.name == "")
					this.callback.apply(null,ret)
				else{	
					isObject(this.callback) 
					? milo.loader.modulemap[this.name] = this.callback
					: milo.loader.modulemap[this.name] = this.callback.apply(null,ret);
				}	
			},
			
			/**
			 * ����Ƿ�����maped�Ķ���
			 * @return {undefined} undefined 
			 * ���޶�ʱ���ڼ��modulemap
			 */
			checkMaped : function(){
				//��modulemap����maped������Ϊres��ӡ�
				for(var i=0 ; i < this.modules.length; i++){
					if (isUndefined(this.res[this.modules[i]]) 
					  && !isUndefined(milo.loader.modulemap[this.modules[i]])
					  ){
						this.res[this.modules[i]] = milo.loader.modulemap[this.modules[i]];
						this.hasmaped ++ ;
					}
				}
				//�������
				if (this.hasmaped == this.needown){
					this.completeLoad.apply(this, [true]);
					return;
				}
				
				//���س�ʱ
				if (this.hasmaped < this.needown && this.expires<=0){
					for(var i=0 ; i < this.modules.length; i++){
						if (!isObject(milo.loader.modulemap[this.modules[i]])){
							this.res[this.modules[i]] = "undefined";
							this.hasmaped ++ ;
						}
					}
					this.completeLoad.apply(this, [false]);
					return;
				}
								
				//��������
				if (this.hasmaped < this.needown  && this.expires>0){			
					this.expires = this.expires - 50;
					var mc = this
					setTimeout(
					function (){
						mc.checkMaped();
					},50);
				}			
			}
		};
	}
	
	/**
	 * load���ؿ�ʼ
	 * @private
	 * @param {object} mc ���ض���
	 * @return {undefined} undefined 
	 */
	function start(mc){	
		var need = mc.need;

		//for(var key=0 ; key < need.length; key++){
	    for(var key in need){			
			//console.log(key+" -> "+milo.loader.modulemap[key])
			 //���modulemap���У���ֱ�ӷ��ء� by dickma  2014.2.10
			if (milo.loader.modulemap[key]){ 
				 mc.loadUrlCallback.apply(mc, [milo.loader.modulemap[key], key]);	
			}else{
			    
				//�ж���û�м��ع� by dickma  2014.1.15
				
				
				var loaded=false;
				for (var i=0;i<queue.length;i++){
				   if (queue[i][0]==key){
					  mc.loadUrlCallback.apply(mc, [queue[i][2], key,i]);
					  loaded=true;
					  break;
				   }
				}
				
				if (!loaded){
				  load(need[key],key,mc);	
				  
				} 
				
			
			}
			
			
		}
		//������״̬
		checkloaded(mc);	
	}
	
	/**
	 * �����ļ�
	 * @private
	 * @param {object} mc ���ض���
	 * @return {undefined} undefined 
	 */
	function load(url, name, mc){
		var isCSS = /\.css(\?|$)/i.test(url);
		loading[name] = true;
		isCSS ? loadCSS(url, function(ret){
				mc.loadUrlCallback.call(mc, [ret, name]);
			}) 
		  	: loadScript(url, function(ret){
				mc.loadUrlCallback.apply(mc, [ret, name]);
			}) ;		
	}
	
	/**
	 * ���������
	 * @private
	 * @param {object} mc ���ض���
	 * @return {undefined} undefined 
	 */
	function checkloaded(mc){
		//�������
		if (mc.hasdown == mc.needown){
			mc.loadInluderCallback.apply(mc, [true]);
			return;
		}
		
		//���س�ʱ
		if (mc.hasdown < mc.needown && mc.expires<=0){
			//**���õ�expires,�Ե����ļ�ʧ�ܵĿ�����ǰ�ж�
			mc.loadInluderCallback.apply(mc, [false]);
			return;
		}
		
		//��������
		if (mc.hasdown < mc.needown  && mc.expires>0){			
			mc.expires = mc.expires - 50;
			setTimeout(
			function (){
				checkloaded(mc);
			},50);
		}
	}
	
	/**
	 * ��ȡ�ű�·��
	 * ��http://��ͷ�ģ�Ϊfullpath
	 * �����ľ���Ϊ���·��
	 * ���·����ģ�鷽ʽ������
	 * @private
	 * @param {string} filepath ·��
	 * @return {string} fullpath ȫ��·�� 
	 */
	function getModulePath(filepath){
		if (filepath.search(/^http:\/\//i) == -1){
			//var loc = window.location.href,    //extend
			//	path = loc.substr(0,loc.lastIndexOf("/"));
			//filepath = filepath.replace(/\./g, "/");
			filepath = milo.config.loaderPath + filepath.replace(/\./g, "/") + ".js"
					 + "?" + milo.config.version;
		}
		return filepath;
	}
	
	/**
	 * ��ȡģ������
	 * ��http://��ͷ�ģ�Ϊfullpath
	 * �����ľ���Ϊ���·��
	 * ���·����ģ�鷽ʽ������
	 * @private
	 * @param {string} filepath ·��
	 * @return {string} fullpath ȫ��·�� 
	 */
	function getModuleName(){
		return null;
	}
	
	/******************************************************/
	/*****************includer����*************************/
	/******************************************************/
	
	/**
	 * includer���ݼ�����
	 * @private
	 * @param {string} name ��������
	 * @param {array} files ����ģ�����
	 * @param {function} callback �ص�����
	 * @return {object} object 
	 */
	function includerContainer(files, callback){	
		var needown = 0,
			hasdown = 0,
			need = {};

		for(var i=0 ; i < files.length; i++){
			var url = getModulePath(files[i]);			
			needown ++;
			if (loaded[files[i]]) {
				hasdown ++;
				break;
			}
			need[files[i]] = url;			
		}

		return {
			files : files,
			need : need,   //������������(����load����)
			res : new Array(), //���������� �����
			expires : needown * milo.config.expires, //����ʱ��
			callback : callback, //ģ�������ɺ�Ļص�
			needown : needown,  //��Ҫ������
			hasdown : hasdown,	//��������

			/**
			 * ���ļ����سɹ���ص�
			 * @private
			 * @param {bool} ret �������
			 * @param {string} name ģ������
			 * @return {undefined} undefined 
			 * ��ȡ�ļ��ڵ�define���󣬴�����mc
			 */
			loadUrlCallback : function(ret, name){
				if(ret)	this.hasdown ++;
				loaded[name] = ret;				
			},		
			
			/**
			 * �����ļ����سɹ���ص�
			 * @private
			 */
			loadInluderCallback : function(ret){
				var res = [];
				for(var i=0 ; i < this.files.length; i++){
					res.push(loaded[this.files[i]]);
				}
				this.callback.apply(null,res);
			}
		};
	}
	
	/******************************************************/
	/*****************���ؽű�ͨ�÷�����*******************/
	/******************************************************/
	
	/**
	 * ���ؽű���������
	 * @private
	 * @param {string} filepath ·��
	 * @param {function} callback �ص�����
	 * @return {undefined} undefined 
	 */
	function loadScript(url, callback){
		var head = document.getElementsByTagName("head")[0];
		var script = document.createElement("script");			
		script.type = "text/javascript";
		script.src = url;
		var timeout = setTimeout(
			function (){
				head.removeChild(script);
				callback.call(this,false);	
			},
			milo.config.expires
		);
		
		onload(
			script,
			function(Ins){
				head.removeChild(script);
				clearTimeout(timeout);
				callback(true);
			}
		);
		head.appendChild(script);
		return true;
	}
	
	/**
	 * ������ʽ��������
	 * �ݲ�������ص����������Ϊ���سɹ���
	 * @private
	 */
	function loadCSS(url, callback){
		var head = document.getElementsByTagName("head")[0];
		var link = head.appendChild(
			document.createElement("link")
		);
		link.href = url;
	    link.rel = 'stylesheet';
		callback.call(this,true);
	}
	
	/**
	 * ���ؽű���ɺ�Ĵ���
	 * @private
	 * @param {dom} node script DOM
	 * @param {function} callback �ص�����
	 * @return {undefined} undefined 
	 */
	function onload(node, callback){		
		var isImpOnLoad = ('onload' in node) ? true :
			(function(){
				node.setAttribute('onload','');
				return typeof node.onload == 'function' ; 
			})();
	
		if(document.addEventListener){
			node.addEventListener('load', function(){
				callback.call(this,node);
			}, false);	
		}
		else if (!isImpOnLoad){
			node.attachEvent ('onreadystatechange', function(){
				var rs = node.readyState.toLowerCase();
				if (rs === 'loaded' || rs === 'complete') {
					node.detachEvent('onreadystatechange');
					callback.call(this,node.innerHTML);
				}
			});
		}
		else{
			//maybe someother browser
		}
	}
	
})(milo.loader);

extend(window, milo.loader);

/**
 * @author cathzhang
 * @version 0.1.0.0
 * @date 2011-08-01
 * @class milo.dom
 * ���������з������󶨵�milo�����У�ͨ����milo.���������е��á�<br/>
 */

namespace("milo.dom");

(function(){
    var dom = milo.dom;
    var userAgent = navigator.userAgent.toLowerCase();
    extend( dom, {
        /**
         * �ж����������
         */
        browser : {
            /**
             * ��ȡ�汾��
             */
            version: (userAgent.match( /.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/ ) || [0,'0'])[1],
            /**
             * �Ƿ�webkit�����
             */
            webkit: /webkit/.test( userAgent ),
            /**
             * �Ƿ�opera�����
             */
            opera: /opera/.test( userAgent ),
            /**
             * �Ƿ�IE�����
             */
            msie: /msie/.test( userAgent ) && !/opera/.test( userAgent ),
            /**
             * �Ƿ�mozilla�����
             */
            mozilla: /mozilla/.test( userAgent ) && !/(compatible|webkit)/.test( userAgent ),
            /**
             * �Ƿ�TT�����
             */
            tt: /tencenttraveler/.test( userAgent ),
            /**
             * �Ƿ�chrome�����
             */
            chrome: /chrome/.test( userAgent ),
            /**
             * �Ƿ�firefox�����
             */
            firefox: /firefox/.test( userAgent ),
            /**
             * �Ƿ�safari�����
             */
            safari: /safari/.test( userAgent ),
            /**
             * �Ƿ�gecko�����
             */
            gecko: /gecko/.test( userAgent ),
            /**
             * �Ƿ�IE6
             */
            ie6: this.msie && this.version.substr(0,1) == '6'

        },

        /**
         * �ж�DOM�����Ƿ������ʽ������
         * @param {dom} element dom����
         * @param {string} className ��ʽ����
         * @return {bool}
         */
        hasClassName : function(element, className) {
            var elementClassName = element.className;
            return (elementClassName.length > 0 && (elementClassName == className ||
                new RegExp("(^|\\s)" + className + "(\\s|$)").test(elementClassName)));
        },

        /**
         * ΪDOM����������ʽ������
         * @param {dom} element dom����
         * @param {string} className ��ʽ����
         * @return {dom}
         */
        addClassName : function(element, className) {
            if (!milo.hasClassName(element, className))
                element.className += (element.className ? ' ' : '') + className;
            return element;
        },

        /**
         * ΪDOM����ɾ����ʽ������
         * @param {dom} element dom����
         * @param {string} className ��ʽ����
         * @return {dom}
         */
        removeClassName : function(element, className) {
            element.className = element.className.replace(
                new RegExp("(^|\\s+)" + className + "(\\s+|$)") , ' ');
            return element;
        },
        /**
         * ��ȡurl�еĲ���ֵ
         * @param {string} pa ��������
         * @return {string} ����ֵ
         */
        request: function(pa){ 
			var url = window.location.href.replace(/#+.*$/, ''),
				params = url.substring(url.indexOf("?")+1,url.length).split("&"),
				param = {} ;
			for (var i=0; i<params.length; i++){  
				var pos = params[i].indexOf('='),//����name=value  
					key = params[i].substring(0,pos),
					val = params[i].substring(pos+1);//��ȡvalue 
				param[key] = val;
			} 
			return (typeof(param[pa])=="undefined") ? "" : param[pa];
		},

        isOwnProperty : function(object, property) {
            return Object.prototype.hasOwnProperty.call(object, property);
        },

        serializeParameters: function(parameters, character){
            var parameter, serialize;

            if (character == null) {
                character = "";
            }
            serialize = character;
            for (parameter in parameters) {
                if (parameters.hasOwnProperty(parameter)) {
                    if (serialize !== character) {
                        serialize += "&";
                    }
                    serialize += "" + (encodeURIComponent(parameter)) + "=" + (encodeURIComponent(parameters[parameter]));
                }
            }
            if (serialize === character) {
                return "";
            } else {
                return serialize;
            }
        },

        mix: function() {
            var arg, argument, child, len, prop;

            child = {};
            arg = 0;
            len = arguments.length;
            while (arg < len) {
                argument = arguments[arg];
                for (prop in argument) {
                    if (milo.isOwnProperty(argument, prop) && argument[prop] !== undefined) {
                        child[prop] = argument[prop];
                    }
                }
                arg++;
            }
            return child;
        },

        /**
         * ���ϱ�����ÿ��key����callback�������������к�ļ���
         * @param
         * @return
         */
        map : function(elements, callback){
            var i, key, value, values;
            values = [];
            i = void 0;
            key = void 0;
            if (isArray(elements)) {
                i = 0;
                while (i < elements.length) {
                    value = callback(elements[i], i);
                    if (value != null) {
                        values.push(value);
                    }
                    i++;
                }
            } else {
                for (key in elements) {
                    value = callback(elements[key], key);
                    if (value != null) {
                        values.push(value);
                    }
                }
            }
            return _flatten(values);
        },

        instance: function(elements, property) {
            return milo.map(elements,function(index, element) {
                return element[property];
            });
        },

        filter: function(elements,selector){
            return ([].filter.call(elements, function(element) {
                return element.parentNode && query(element.parentNode, selector).indexOf(element) >= 0;
            }));
        },


        each : function(elements, callback) {
            var i, key;
            i = void 0;
            key = void 0;
            if (isArray(elements)) {
                i = 0;
                while (i < elements.length) {
                    if (callback.call(elements[i], i, elements[i]) === false) {
                        return elements;
                    }
                    i++;
                }
            } else {
                for (key in elements) {
                    if (callback.call(elements[key], key, elements[key]) === false) {
                        return elements;
                    }
                }
            }
            return elements;
        },

        remove: function(element){
            if(element.parentNode != null){
                element.parentNode.removeChild(element);
            }
        },

        parent: function(element,selector) {
            var ancestors;
            ancestors = (selector ? _findAncestors(element) : milo.instance(element,"parentNode"));
            return _filtered(ancestors, selector);
        },

        children: function(elements, selector){
            var children_elements;

            children_elements = milo.map(elements, function(index, element) {
                return Array.prototype.slice.call(element.children);
            });
            return _filtered(children_elements, selector);
        },

        get: function(elements, index){
            if (index === undefined) {
                return elements;
            } else {
                return elements[index];
            }
        },

        first: function(elements){
            return elements[0];
        },

        last: function(elements){
            return elements[elements.length - 1];
        },

        find: function(elements, selector) {
            var result;

            if (elements.length === 1) {
                result = query(elements[0], selector);
            } else {
                result = milo.map(elements,function(index, element) {
                    return query(element, selector);
                });
            }
            return result;
        },

        show: function(element){
            return element.style("display", "block");
        },

        hide: function(element) {
            return element.style("display", "none");
        },

        height: function(element) {
            var offset;
            offset = element.offset();
            return offset.height;
        },

        width: function() {
            var offset;
            offset = this.offset();
            return offset.width;
        },

        offset: function(element) {
            var bounding;
            bounding = element.getBoundingClientRect();
            return {
                left: bounding.left + window.pageXOffset,
                top: bounding.top + window.pageYOffset,
                width: bounding.width,
                height: bounding.height
            };
        },

        append: function(elements, value){
            return milo.each(elements,function(i,element) {
                if (isString(value)) {
                    return element.insertAdjacentHTML("beforeend", value);
                } else if (isArray(value)) {
                    return milo.each(value, function(index, v) {
                        return element.appendChild(v);
                    });
                } else {
                    return element.appendChild(value);
                }
            });
        },

        prepend: function(elements,value) {
            return milo.each(elements, function(i,element) {
                if (isString(value)) {
                    return element.insertAdjacentHTML("afterbegin", value);
                } else if (isArray(value)) {
                    return value.each(function(index, v) {
                        return element.insertBefore(v, element.firstChild);
                    });
                } else {
                    return element.insertBefore(value, element.firstChild);
                }
            });
        },

        text: function(element,value) {
            if (value || toType(value) === "number") {
                return element.textContent = value;
            } else {
                return element.textContent;
            }
        },

        html: function(element, value) {
            var type;
            type = toType(value);
            if (value || type === "number" || type === "string") {
                if (type === "string" || type === "number") {
                    return element.innerHTML = value;
                } else {
                    element.innerHTML = null;
                    var _i, _len, _results;
                    if (type === "array") {
                        _results = [];
                        for (_i = 0, _len = value.length; _i < _len; _i++) {
                            element = value[_i];
                            _results.push(element.appendChild(element));
                        }
                        return _results;
                    } else {
                        return element.appendChild(value);
                    }
                }
            } else {
                return element.innerHTML;
            }
        },

        /**
         * Ϊdom����������ʽ
         * @param {dom} ele dom����
         * @param {object} styles ��ʽ���� like:{width:100,height:100}
         * @return undefined
         */
        setStyle: function(ele, styles){
            for (var i in styles) {
                ele.style[i] = styles[i];
            }
        },

        /**
         * Ϊdom�����ȡѡ�����Ե���ʽ
         * @param {dom} ele dom����
         * @param {string} prop ��������
         * @return ������ʽ
         */
        getStyle: function(el, prop){
            var viewCSS = isFunction(document.defaultView) //(typeof document.defaultView == 'function')
                ? document.defaultView()
                : document.defaultView;
            if (viewCSS && viewCSS.getComputedStyle) {
                var s = viewCSS.getComputedStyle(el, null);
                return s && s.getPropertyValue(prop);
            }
            return (el.currentStyle && (el.currentStyle[prop] || null) || null);
        },

        /**
         * ��ȡҳ�����߶�
         * @return ������ʽ
         */
        getMaxH: function(){
            return (this.getPageHeight() > this.getWinHeight() ? this.getPageHeight() : this.getWinHeight())
        },

        /**
         * ��ȡҳ�������
         * @return ������ʽ
         */
        getMaxW: function(){
            return (this.getPageWidth() > this.getWinWidth() ? this.getPageWidth() : this.getWinWidth())
        },

        /**
         * ��ҳ���ݸ߶�
         * @return {int} ��ҳ���ݸ߶�
         */
        getPageHeight: function(){
            var h = (window.innerHeight && window.scrollMaxY) ? (window.innerHeight + window.scrollMaxY) : (document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight);
            return h > document.documentElement.scrollHeight ? h : document.documentElement.scrollHeight
        },

        /**
         * ��ҳ���ݿ��
         * @return {int} ��ҳ���ݿ��
         */
        getPageWidth: function(){
            return (window.innerWidth && window.scrollMaxX) ? (window.innerWidth + window.scrollMaxX) : (document.body.scrollWidth > document.body.offsetWidth ? document.body.scrollWidth : document.body.offsetWidth);
        },

        /**
         * �������������߶�
         * @return {int} ����������߶�
         */
        getWinHeight: function(){
            return (window.innerHeight) ? window.innerHeight :
                (document.documentElement && document.documentElement.clientHeight)
                    ? document.documentElement.clientHeight
                    : document.body.offsetHeight
        },

        /**
         * ���������������
         * @return {int} ������������
         */
        getWinWidth: function(){
            return (window.innerWidth) ? window.innerWidth : (document.documentElement && document.documentElement.clientWidth) ? document.documentElement.clientWidth : document.body.offsetWidth
        },

        /**
         * ����dom͸����
         * @param {dom} ele dom����
         * @param {int} level ͸����ֵ��0-100��������
         * @return {undefined}
         */
        setOpacity: function(ele, level){
            //level = Math.min(1,Math.max(level,0));
            if(this.browser.msie && (!document.documentMode || document.documentMode < 9)){
                ele.style.filter = 'Alpha(opacity=' + level + ')'
            }else{
                ele.style.opacity = level / 100
            }
        },
        /**
         * ��ȡҳ���ж���ľ���Xλ��
         * @param {dom} e dom����
         * @return {int}
         */
        getX: function(e) {
            var t = e.offsetLeft;
            while (e = e.offsetParent) t += e.offsetLeft;
            return t
        },
        /**
         * ��ȡҳ���ж���ľ���Yλ��
         * @param {dom} e dom����
         * @return {int}
         */
        getY: function(e) {
            var t = e.offsetTop;
            while (e = e.offsetParent) t += e.offsetTop;
            return t
        }
    });
    function _flatten(array){
        if (array.length > 0) {
            return [].concat.apply([], array);
        } else {
            return array;
        }
    }

    function _findAncestors (nodes) {
        var ancestors;
        ancestors = [];
        while (nodes.length > 0) {
            nodes = milo.map(nodes, function(index, node) {
                if ((node = node.parentNode) && node !== document && ancestors.indexOf(node) < 0) {
                    ancestors.push(node);
                    return node;
                }
            });
        }
        return ancestors;
    };

    function _filtered(nodes, selector) {
        if (selector === undefined) {
            return (nodes);
        } else {
            return milo.filter(nodes,selector);
        }
    };

})();

/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2011-07-21
 * @demo http://gameact.qq.com/milo/core/bases.html
 * @class milo.array 
 * ����������������ԭ��oss_base.js��Ϊarray������ӵĲ���ԭ�ͷ�����<br/>
 * �޸�������£�<br/>
 * ���ӷ���getLength,getArrayKey,hasValue,filter,unique<br/>
 * ���������з������󶨵�milo�����У�ͨ����milo.���������е��á�<br/> 
 * <p>
 * Example:
 * <pre><code>
var a=['1','2','3','4'] ;
var b=['1','2','5','23432',2] ;
alert(milo.filter(a,b))  //["3","4"] 
var c = milo.unique(a,b)
alert(c);				 //���["3","4",'5','23432'] 
 *</code></pre>
 * </p>
 
 */
 
namespace("milo.array");

(function(){
var array = milo.array;
extend( array, {
	/**
	 * �ж��������ݸ���
	 * @param {array} array ����
	 * @return {int} ����
	 */
	getLength : function(arr){
		var l = 0;
		for(var key in arr){
			l ++;
		}	
		return l;
	},
	/**
	 * ��������
	 * @param {array} array ����
	 * @return {array} ���������
	 */
	clone : function(arr){
		var a = [];
		for(var i=0; i<arr.length; ++i) {
			a.push(arr[i]);
		}
		return a;
	},
	/**
	 * �ж��������Ƿ�������ֵ
	 * @param {array} arr �������
	 * @param {object} value ����
	 * @return {bool} ��/��
	 */
	hasValue : function(arr, value){
		var find = false;
		if (isArray(arr) || isObject(arr))
			for(var key in arr){
				if (arr[key] == value) find = true;
			}
		return find;
	},
	/**
	 * ����ֵ��������е�key
	 * @param {array} arr �������
	 * @param {object} value ����
	 * @return {string} key
	 */
	getArrayKey : function(arr, value){
		var findKey = -1;
		if (isArray(arr) || isObject(arr))
			for(var key in arr){
				if (arr[key] == value) findKey = key;
			}
		return findKey;
	},
	/**
	 * ����a1������a2û�е�ֵ
	 * @param {array} a1 �������
	 * @param {array} a2 �������
	 * @return {array} key
	 */
	filter : function (a1, a2) {
		var res = [];
		for(var i=0;i<a1.length;i++) {
			if(!milo.hasValue(a2, a1[i]))
				res.push(a1[i]);
		}
		return res;
	},
	/**
	 * ���������ֵ�Ľ���
	 * @param {array} arr ����
	 * @param {array} arr ����
	 * @return {array} key
	 */
	unique : function (a1, a2) {
		return milo.filter(a1,a2).concat(milo.filter(a2,a1))
	} 
});
})();/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2011-07-21
 * @class milo.string �ַ�����
 * ����������������ԭ��oss_base.js��Ϊstring������ӵ�ԭ�ͷ���<br/>
 * �޸�������£�<br/>
 * ɾ������replaceAll��ʹ������/g�����ѳ�ȫ��<br/>
 * �޸ķ���replacePairs�����õ�replaceAll������Ϊ��replace���������򷽷�<br/>
 * ɾ������encode������ֱ�ӿ���escape<br/>
 * ɾ������unencode������ֱ�ӿ���unescape<br/>
 * ɾ������toInputValue: ��toHTML����<br/>
 * ɾ������toTextArea: ��toHTML����<br/>
 * ɾ������isEmpty: ���岻���Ƿ���Ҫtrim���ж�length�������Ҫʵ����Ҫ������<br/>
 * ��������isAllNumΪisNumberString<br/>
 * �Ƴ�����isInt��milo.number��<br/>
 * �Ƴ�����isFloat��milo.number��<br/>
 * �Ƴ�����isQQ��milo.number��<br/>
 * ���������з������󶨵�milo�����У�ͨ����milo.���������е��á�<br/> 
 * <p>
 * Example:
 * <pre><code>
milo.trim(" test ")
 *</code></pre>
 * </p> 
 */
 
namespace("milo.string");

(function(){

var string = milo.string;
extend( string, {
	/**
	 * �����ַ������ֽڳ���<br/>
	 * ������2 Ӣ����1<br/>
	 * @param {string} str �ַ���
	 * @return {int}
	 */
	getByteLength : function(str){
		var bytes=0,i=0;
		for (; i<str.length; ++i,++bytes) {
			if ( str.charCodeAt(i) > 255 ) {
					++bytes;
			}
		}
		return bytes;
	},
	/**
	 * �����ж��ٸ�˫�ֽ��ַ�
	 * @param {string} str �ַ���
	 * @return {int}
	 */
	getDwordNum : function(str){
		return string.getByteLength(str) - str.length;
	},
	/**
	 * �����ж��ٸ������ַ�
	 * @param {string} str �ַ���
	 * @return {int}
	 */
	getChineseNum : function(str){
		return str.length - str.replace(/[\u4e00-\u9fa5]/g,"").length;
	},
	/**
	 * ��ȡ�����ַ���<br/>
	 * ȡiMaxBytes �����һ�������ַ����ֵĵط��滻�ַ�<br/>
	 * @param {string} str �ַ���
	 * @param {int} iMaxBytes �ַ���
	 * @param {string} sSuffix �油�ַ���
	 * @return {string}
	 */
	cutChinese : function(str, iMaxBytes, sSuffix){
		if(isNaN(iMaxBytes)) return str;
		if(string.getByteLength(str)<=iMaxBytes) return str;
		var i=0, bytes=0;
		for (; i<str.length && bytes<iMaxBytes; ++i,++bytes) {
			if ( str.charCodeAt(i) > 255 ) {
					++bytes;
			}
		}
		sSuffix = sSuffix || "";
		return (bytes-iMaxBytes == 1 ? str.substr(0,i-1) : str.substr(0,i) ) + sSuffix;
	},
	/**
	 * ȥ���ַ�����ߵķǿ��ַ�
	 * @param {string} str �ַ���
	 * @return {string}
	 */
	trimLeft : function(str){
		return str.replace(/^\s+/,"");
	},
	/**
	 * ȥ���ַ����ұߵķǿ��ַ�
	 * @param {string} str �ַ���
	 * @return {string}
	 */
	trimRight : function(str){
		return str.replace(/\s+$/,"");
	},
	/**
	 * ȥ���ַ����������ߵķǿ��ַ�
	 * @param {string} str �ַ���
	 * @return {string}
	 */
	trim : function(str){
		return milo.trimRight(milo.trimLeft(str));
	},
	/**
	 * �ɶ��ַ����滻
	 * @param {string} str �ַ���
	 * @param {array} str �ַ���<br/>
	      array�������� [0] �������ݣ�[1] �滻����<br/>
		  array���Գ��ֶ��<br/>
	 * @return {string}
	 */
	replacePairs : function(){
		var str = arguments[0];
		for (var i=1; i<arguments.length; ++i) {
			var re = new RegExp(arguments[i][0], "g"); 
			str = str.replace(re, arguments[i][1]);
		}
		return str;
	},
	/**
	 * �ַ����滻ΪHTML������ʽ
	 * @param {string} str �ַ���
	 * @return {string}
	 */
	toHtml : function(str){
		var CONVERT_ARRAY =
		[
			["&", "&#38;"],
			[" ", "&#32;"],
			["'", "&#39;"], 
			["\"", "&#34;"],
			["/", "&#47;"],
			["<", "&#60;"],
			[">", "&#62;"],
			["\\\\", "&#92;"],
			["\n", "<br />"],
			["\r", ""]
		];
		return milo.replacePairs.apply(this, [str].concat(CONVERT_ARRAY));
	},
	/**
	 * У�������ַ
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isMail : function(str){
		return /^(?:[\w-]+\.?)*[\w-]+@(?:[\w-]+\.)+[\w]{2,3}$/.test(str);    
	},
	/**
	 * У����ͨ�绰��������룺���ԡ�+����ͷ���������⣬�ɺ��С�-��
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isTel : function(str){
		return /^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/.test(str);
	},
	/**
	 * У���ֻ����룺���������ֿ�ͷ
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isMobile : function(str){
		return /^1[34578]\d{9}$/.test(str);
	},
	/**
	 * У����������
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isZipCode : function(str){
		return /^(\d){6}$/.test(str);
	},
	/**
	 * �Ƿ����֤����
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isIDCard : function(str){
		var C15ToC18 = function(c15) {
			var cId=c15.substring(0,6)+"19"+c15.substring(6,15);
			var strJiaoYan  =[  "1", "0", "X", "9", "8", "7", "6", "5", "4", "3", "2"];
			var intQuan =[7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
			var intTemp=0;
			for(i = 0; i < cId.length ; i++)
			intTemp +=  cId.substring(i, i + 1)  * intQuan[i];  
			intTemp %= 11;
			cId+=strJiaoYan[intTemp];
			return cId;
		}
		var Is18IDCard = function(IDNum) {
			var aCity={11:"����",12:"���",13:"�ӱ�",14:"ɽ��",15:"���ɹ�",21:"����",22:"����",23:"������",31:"�Ϻ�",32:"����",33:"�㽭",34:"����",35:"����",36:"����",37:"ɽ��",41:"����",42:"����",43:"����",44:"�㶫",45:"����",46:"����",50:"����",51:"�Ĵ�",52:"����",53:"����",54:"����",61:"����",62:"����",63:"�ຣ",64:"����",65:"�½�",71:"̨��",81:"���",82:"����",91:"����"};
		
			var iSum=0, info="", sID=IDNum;
			if(!/^\d{17}(\d|x)$/i.test(sID)) {
				return false;
			}
			sID=sID.replace(/x$/i,"a");
		
			if(aCity[parseInt(sID.substr(0,2))]==null) {
				return false;
			}
			
			var sBirthday=sID.substr(6,4)+"-"+Number(sID.substr(10,2))+"-"+Number(sID.substr(12,2));
			var d=new Date(sBirthday.replace(/-/g,"/"))
			
			if(sBirthday!=(d.getFullYear()+"-"+ (d.getMonth()+1) + "-" + d.getDate()))return false;
			
			for(var i = 17;i>=0;i --) iSum += (Math.pow(2,i) % 11) * parseInt(sID.charAt(17 - i),11)
			
			if(iSum%11!=1)return false;
			return true;
		}
		
		return str.length==15 ? Is18IDCard(C15ToC18(str)) : Is18IDCard(str);
	},	
	/**
	 * �Ƿ�ȫ��������
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isChinese : function(str){
		return milo.getChineseNum(str)==str.length ? true : false;
	},
	/**
	 * �Ƿ�ȫ����Ӣ��
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isEnglish : function(str){
		return /^[A-Za-z]+$/.test(str);
	},
	/**
	 * �Ƿ����ӵ�ַ
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isURL : function(str){
		return /^http:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/.test(str);
	},
	/**
	 * �Ƿ������ַ���
	 * @param {string} str �ַ���
	 * @return {bool}
	 */
	isNumberString : function(str){
		return /^\d+$/.test(str);
	}
})
})();/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2011-08-01
 * @class milo.cookie  
 * ����������������ԭ��oss_base.js�е�Cookie����<br/>
 * �޸�������£�<br/>
 * ��clear��������sDomain, sPath���������򷽷���Ч��<br/> 
 */
 
namespace("milo.cookie");

(function(){
var cookie = milo.cookie;
extend( cookie, {
	/**
	 * ����cookie
	 * @param {string} sName cookie��
	 * @param {string} sValue cookieֵ
	 * @param {int} iExpireSec ʧЧʱ�䣨�룩
	 * @param {string} sDomain ������
	 * @param {string} sPath ����·��
	 * @param {bool} bSecure �Ƿ����
	 * @return {void}
	 */
	set : function(sName,sValue,iExpireSec,sDomain,sPath,bSecure){
		if(sName==undefined) {
			return;
		}
		if(sValue==undefined) {
			sValue="";
		}
		var oCookieArray = [sName+"="+escape(sValue)];
		if(!isNaN(iExpireSec)){
			var oDate = new Date();
			oDate.setTime(oDate.getTime()+iExpireSec*1000);
			oCookieArray.push("expires=" + oDate.toGMTString());
		}
		if(sDomain!=undefined){
			oCookieArray.push("domain="+sDomain);
		}
		if(sPath!=undefined){
			oCookieArray.push("path="+sPath);
		}
		if(bSecure){
			oCookieArray.push("secure");
		}
		document.cookie=oCookieArray.join("; ");
	},
	/**
	 * ��ȡcookie
	 * @param {string} sName cookie��
	 * @param {string} sValue Ĭ��ֵ
	 * @return {string} cookieֵ
	 */
	get : function(sName,sDefaultValue){
		var sRE = "(?:; |^)" + sName + "=([^;]*);?";
		var oRE = new RegExp(sRE);
		
		if (oRE.test(document.cookie)) {
			return unescape(RegExp["$1"]);
		} else {
			return sDefaultValue||null;
		}
	},
	/**
	 * ��ȡcookie
	 * @param {string} sName cookie��
	 * @param {string} sDomain ������
	 * @param {sPath} sPath ����·��
	 * @return {void} 
	 */
	clear : function(sName, sDomain, sPath){
		var oDate = new Date();
		cookie.set(sName,"", -oDate.getTime()/1000, sDomain, sPath);
	}	
});
})();

/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2011-07-21
 * @class milo.date
 * ����������������ԭ��oss_base.js��Ϊdate������ӵĲ���ԭ�ͷ���<br/>
 * �޸�������£�<br/>
 * ������toShortDateStringΪtoDateString<br/>
 * ������toShortStringΪtoDateTimeString<br/> 
 * ���������з������󶨵�milo�����У�ͨ����milo.���������е��á�<br/> 
 * <p>
 * Example:
 * <pre><code>
console.log(milo.toDateString('/')) // 2011/10/21
 *</code></pre>
 * </p>
 */
 
namespace("milo.date");
(function(){
var date = milo.date;
var _d = new Date();
extend( date, {
	/**
	 * ��ȡ����
	 * @param {string} sep �ָ��� Ĭ��Ϊ-
	 * @return {string} yyyy-mm-dd
	 */
	toDateString : function(nd){	
		var a=[],
			dt = isDate(nd) ? nd : _d;
			m = dt.getMonth()+1,
			d = dt.getDate(),
			sep = arguments[1] ? arguments[1] : (isString(arguments[0]) ? arguments[0] : "-"); 
		a.push(dt.getFullYear());
		a.push( m.toString().length < 2 ? "0" + m : m);
		a.push( d.toString().length < 2 ? "0" + d : d);
		return a.join(sep);
	},
	/**
	 * ��ȡ���ں�ʱ��
	 * @param {string} sep �ָ��� Ĭ��Ϊ-
	 * @return {string} yyyy-mm-dd hh:ii:ss
	 */
	toDateTimeString : function(nd){
	    var dt = isDate(nd) ? nd : _d,
			h = dt.getHours(),
			i = dt.getMinutes(),
			s = dt.getSeconds(),
			a = [];
		a.push(h.toString().length < 2 ? "0" + h : h);
		a.push(i.toString().length < 2 ? "0" + i : i);
		a.push(s.toString().length < 2 ? "0" + s : s);
		return date.toDateString.apply(this,arguments) + " " + a.join(":");
	},
	/**
	 * �Ƿ�����
	 * @param {int} year ���
	 * @return {bool} ��/��
	 */
	isLeapYear : function(year) {
		return (0 == year % 4 && ((year % 100 != 0) || (year % 400 == 0)))
	},
	/**
	 * ��ȡ������ʱ��
	 * @return {date} Date
	 */
	getSeverDateTime : function(){
		var xhr = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
		xhr.open("HEAD", window.location.href, false);
		xhr.send();	
		var d= new Date(xhr.getResponseHeader("Date"));
		return d;
	}	
});
})();/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2011-07-21
 * @class milo.number 
 * ����������������ԭ��oss_base.js��Ϊstring������ӵĲ���ԭ�ͷ���<br/>
 * ���������з������󶨵�milo�����У�ͨ����milo.���������е��á�<br/> 
 * <p>
 * Example:
 * <pre><code>
milo.isQQ(12345456)
 *</code></pre>
 * </p> 
 */
 
namespace("milo.number");

(function(){

var number = milo.number;
extend( number, {
	/**
	 * �Ƿ�ĳһ��Χ������
	 * @param {int} n ��ֵ
	 * @param {int} iMin ��Χ��ֵ
	 * @param {int} iMax ��Χ��ֵ
	 * @return {bool} 
	 */
	isInt : function(n, iMin, iMax){
		if(!isFinite(n)) {
			return false;
		}
		if(!/^[+-]?\d+$/.test(n)) {
			return false;   
		}
		if(iMin!=undefined && parseInt(n)<parseInt(iMin)) {
			return false;
		}
		if(iMax!=undefined && parseInt(n)>parseInt(iMax)) {
			return false;
		}    
		return true;
	},
	/**
	 * �Ƿ�ĳһ��Χ������
	 * @param {float} n ��ֵ
	 * @param {float} fMin ��Χ��ֵ
	 * @param {float} fMax ��Χ��ֵ
	 * @return {bool} 
	 */
	isFloat : function(n, fMin, fMax){
		if(!isFinite(n)) {
			return false;
		}
		if(fMin!=undefined && parseFloat(n)<parseFloat(fMin)) {
			return false;
		}
		if(fMax!=undefined && parseFloat(n)>parseFloat(fMax)) {
			return false;
		}
		return true;
	},
	/**
	 * �Ƿ�QQ����
	 * @param {int} qq qq��
	 * @return {bool} 
	 */
	isQQ : function(qq){
		return /^[1-9]{1}\d{4,11}$/.test(qq); 
		// /^[1-9]\d{4,11}$/.test(qq) && parseInt(qq)<=4294967294;   
	},
	/**
	 * ȡ�������
	 * @param {int} n ����
	 * @return {int} 0~n����������
	 */
	randomInt : function(n){
		return Math.floor(Math.random() * n);
	}
});
})();/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2011-08-01
 * @class milo.event 
 * ���������з������󶨵�milo�����У�ͨ����milo.���������е��á�<br/> 
 * <p>
 * Example:
 * <pre><code>
milo.addEvent(g('getString'),'click',function(e){
	alert(isString(g('string').value))
})
 *</code></pre>
 * </p>
 */
 
namespace("milo.event");

(function(){
var event = milo.event;
extend( event, {	
	/**
	 * ΪDOM���������¼�
	 * @param {dom} element dom����
	 * @param {string} type �¼�����
	 * @param {function} type �¼�����
	 * @return {undefined} 
	 */
	addEvent : function(el, type, fn){
		if (window.addEventListener){
			el.addEventListener(type, fn, false);	
		}		
		else{
			el['on' + type] = fn;
		}
	},	
	/**
	 * ΪDOM�����Ƴ��¼�
	 * @param {dom} element dom����
	 * @param {string} type �¼�����
	 * @param {function} type �¼�����
	 * @return {undefined} 
	 */
	removeEvent : function (el, type, fn){
		if (window.removeEventListener){
			el.removeEventListener(type, fn, false);
		}
		else{
			el['on' + type] = null;
		}
	},
	isReady : false,
	readyFn : [],
	/**
	 * dom ready�¼�
	 * @param {dom} element dom����
	 * @param {string} type �¼�����
	 * @param {function} type �¼�����
	 * @return {undefined} 
	 */
	ready : function (fn){	
		bindReadyEvent();	
		if ( milo.isReady ){
			fn.call();
		}
		else {
			if (isFunction(fn)){
				milo.readyFn.push(fn);
			}
		}
	},
	
	/**
	* �÷������ڰ󶨵���¼�����һ���click�¼���Ӧ�ٶȿ�2����
	* @param {dom} obj Ҫ�󶨵�dom����
	* @param {function} fun �¼������ĺ���
	*  @return {undefined} 
	*/	
	touchClick: function(obj, fun) {

		var start_x = 0,
			start_y = 0;
		obj.addEventListener('touchstart',function(e){
			start_x = e.touches[0].clientX;
			start_y = e.touches[0].clientY;
			document.addEventListener('touchend', touEnd, false);
		});
		function touEnd(e){
			var endX = e.changedTouches[0].clientX;
			var endY = e.changedTouches[0].clientY;
			if(Math.abs(endX - start_x) < 5 && Math.abs(endY - start_y) < 5) {
				fun.call(obj,e);
			}
			document.removeEventListener('touchend', touEnd, false);
		};
	},
    /**
     * ֹͣ�¼���������
     * @param {event} e �¼�
     * @return {dom}
     */
    preventDefault : function(e){
        if (e.preventDefault){
            e.preventDefault();
        }
        else{
            e.returnValue = false;
        }
    },
    /**
     * ��ֹ�¼�ð�ݴ���
     * @param {event} e �¼�
     * @return {dom}
     */
    stopPropagation : function(e){
        if (e.stopPropagation){
            e.stopPropagation();
        }
        else{
            e.cancelBubble = true;
        }
    }
});



function bindReadyEvent(){
   
	if(document.readyState === 'complete'){
		return ready();
	}
	if(document.addEventListener){
		document.addEventListener("DOMContentLoaded", function(){
			document.removeEventListener("DOMContentLoaded", arguments.callee, false);
			ready();
		},false);
		window.addEventListener("load", ready, false);
	}
}

function ready(){
    
	  
	if(!milo.isReady ){
		if(!document.body){
			return setTimeout(ready,13);
		}
		
	
		milo.isReady = true;
		//��Ԥ���صĺϲ��ļ����Define������ԤNeedһ��,������ԭ�߼����ӵ�map�� add by dickma
		milo.loader.preNeed();
		
		//��Dom������Ϻ����milo.ready()���лص������������ִ��
		if(milo.readyFn.length >0){
			var i=0,fn;
		
			while(fn = milo.readyFn[i++]){	
					fn.call();
			}	
			
			milo.readyFn.length = 0;
		}
	
	
			
	}
}

})();/**
 * @author cathzhang
 * @version 0.1.0.0 
 * @date 2012-06-01
 * @class milo.object  
 * ������ͨ�÷���
 */
 
namespace("milo.object");

(function(){

extend( milo.object, {
	/**
	 * ���л�JSON����
	 * ��objectת��Ϊurl�����ַ����������Լ���&�ָ�����a=1&b=2&c=3
	 * ��������Ϊstring �����encodeURIComponent����
	 * ��������Ϊbool ����0����false 1����true
	 * ��������Ϊ�������������еݹ����л�
	 * ��������Ϊfunction �򷵻�function.toString
	 * @param {object} jsonObj json����
	 * @return {string}
	 */
	serialize : function(jsonObj){
		var newJsonObj = null;
		if (typeof(jsonObj) == 'undefined' || typeof(jsonObj) == 'function') 
			newJsonObj = '';
		if (typeof(jsonObj) == 'number') 
			newJsonObj = jsonObj.toString();			
		if (typeof(jsonObj) == 'boolean') 
			newJsonObj = (jsonObj) ? '1' : '0';
		if (typeof(jsonObj) == 'object') {
			if (!jsonObj) newJsonObj = '';
			if (jsonObj instanceof RegExp) newJsonObj = jsonObj.toString();
		}
		if (typeof(jsonObj) == 'string') 
			newJsonObj = jsonObj;		
		if (typeof(newJsonObj) == 'string') 
			return encodeURIComponent(newJsonObj);
			
		var ret = [];
		if (jsonObj instanceof Array) {
			for (var i = 0; i < jsonObj.length; i++) {
				if (typeof(jsonObj[i]) == 'undefined') 	continue;
				ret.push(typeof(jsonObj[i]) == 'object' ? '' : milo.serialize(jsonObj[i]))
			}
			return ret.join('|')
		} 
		else {
			for (var i in jsonObj) {				
				if (typeof(jsonObj[i]) == 'undefined') 	continue;
				newJsonObj = null;
				if (typeof(jsonObj[i]) == 'object') {
					if (jsonObj[i] instanceof Array) {
						newJsonObj = jsonObj[i];
						ret.push(i + '=' + milo.serialize(newJsonObj));
					} else {
						ret.push(i + '=')
					}
				} else {
					newJsonObj = jsonObj[i];
					ret.push(i + '=' + milo.serialize(newJsonObj));
				}
			}
			return ret.join('&')
		}
	},
	/**
	 * �����л�ΪJSON����
	 * ��url������ʽ�Ķ������л���ΪJSON����
	 * ��serialize���Ӧ
	 * @param {object} jsonObj json����
	 * @return {string}
	 */
	unSerialize : function(jsonStr, de){
		de = de || 0;
		jsonStr = jsonStr.toString();
		if (!jsonStr) return {};
		var retObj = {}, 
			obj1Ret = jsonStr.split('&');
		if (obj1Ret.length == 0) return retObj
		for (var i = 0; i < obj1Ret.length; i++) {
			if (!obj1Ret[i]) continue;
			var ret2 = obj1Ret[i].split('=');
			if (ret2.length >= 2) {
				var ret0 = obj1Ret[i].substr(0, obj1Ret[i].indexOf('=')),
					ret1 = obj1Ret[i].substr(obj1Ret[i].indexOf('=') + 1);
				if (!ret1) ret1 = '';
				if (ret0) retObj[ret0] = de == 0? decodeURIComponent(ret1) : ret1;
			}
		}
		return retObj;
	},
	/**
	 * ������object����utf8��ʽ��url����
	 * @param {object} newopt �������
	 * @return {object} �ѽ������
	 */
	decode : function(newopt) {
		if (typeof(newopt) == 'string') {
			try {
				return decodeURIComponent(newopt)
			} catch(e) {}
			return newopt
		}
		if (typeof(newopt) == 'object') {
			if (newopt == null) {
				return null
			}
			if (newopt instanceof Array) {
				for (var i = 0; i < newopt.length; i++) {
					newopt[i] = milo.decode(newopt[i])
				}
				return newopt
			} else if (newopt instanceof RegExp) {
				return newopt
			} else {
				for (var i in newopt) {
					newopt[i] = milo.decode(newopt[i])
				}
				return newopt
			}
		}
		return newopt
	}
	
});
})();/**
 * @author cathzhang
 * @version 0.1.0.0 2011-08-12
 */

milo.base.extend(milo, milo.dom);
milo.base.extend(milo, milo.array);
milo.base.extend(milo, milo.string);
milo.base.extend(milo, milo.date);
milo.base.extend(milo, milo.number);
milo.base.extend(milo, milo.event);
milo.base.extend(milo, milo.object);
milo.base.extend(milo, milo.browser);
milo.base.extend(milo, milo.data);
/**
 * @author chaozhou
 * @version 0.1.0.0 
 * @date 2013-08-15
 * data����ģ��<br/>
 */
 
namespace("milo.data");

(function(){
var data = milo.data;
extend( data, {
	/**
	 * ����data
     * @param {string} sKey data��
	 * @param {string} sValue dataֵ
	 * @return {void}
	 */
	set : function(sKey,sValue){
        if(sKey==undefined) {
            return;
        }
        if(sValue==undefined) {
            sValue="";
        }
        localStorage.setItem(sKey, sValue);
	},
	/**
	 * ��ȡdata
	 * @param {string} sKey data��
	 * @return {string} dataֵ
	 */
	get : function(sKey){
		return localStorage.getItem(sKey);
	},
	/**
	 * ���data
	 * @param {string} sKey data��
	 * @return {void}
	 */
	clear : function(sKey){
		localStorage.removeItem(sKey);
	}
});
})();


namespace("milo.ams");
(function(){
	/**
	 * ��ȡams�ĳ�ʼ��Ϣ
	 * @param {number} amsActivityId ���
	 * @param {function} callback �ص�����
	 * @return {undefined}
	 */
	function getAmsFile(amsActivityId,flowId, callback){
		if(!isFunction(callback)) callback = function(obj){};
		
		var cur_actdesc = window["ams_actdesc_"+amsActivityId];
		
		if(isObject(cur_actdesc)){
			callback(cur_actdesc);
			return;
		}
		if (!amsActivityId || isNaN(amsActivityId) || amsActivityId<=0) return;
			
		var _url = "http://" + window.location.host + "/comm-htdocs/js/ams/v0.2R02/act/" + amsActivityId + "/act.desc.js";
		include(_url, function(loaded){
			if (!loaded) return;
            callback(window["ams_actdesc_"+amsActivityId]);
			return;
		});		
	}
	
	/*
	 * init,submit ����
	 */
	function getDesc(obj, callback){
		
		var actDesc = window["ams_actdesc_" + obj.actId],
			_url = "http://" + window.location.host + "/comm-htdocs/js/ams/v0.2R02/act/" + obj.actId + "/act.desc.js";
		
		if(isObject(actDesc)){
			callback(obj,actDesc);
			return;
		}
		
		include(_url, function(loaded){
		
			callback(obj,window["ams_actdesc_" + obj.actId]);
			return;
		});		
		
	}
	
	function init(obj){
	
		getDesc(obj, function(obj, descData){
			var flows = descData.flows,
				flow = null,
				cfg = obj;

			// �������̺�ƥ�䵽����
			for(fid in flows){
				if (fid == "f_" + obj.flowId){
					flow = flows[fid];
					break
				}
			}
			
			// û��ƥ�䵽 
			if(flow == null){
				return;
			}
			
			// �ж��Ƿ�Ϊ�Զ�������
			if(flow.functions[0].sExtModuleId == null){
			
				need("ams.flowengine",function(FlowEngine){
				
					// �ύ����
					FlowEngine.submit(window['amsCfg_' + obj.flowId]);
					
				});
			}else{
				
				var modName = flow.functions[0].method;
				
				// ����ģ������(�������·��)
				if(obj.modJsPath && obj.modJsPath.indexOf('http') === -1){
					
				}else if(obj.modJsPath){
					
				}
				
			
				need("ams." + modName, function(){
					var module = modName.split("."),
						mn = module[module.length-1],
						newObj = window[mn+"_" + obj.flowId];
					
					if(isObject(newObj) && isFunction(obj.modSubmit)){
						if(!isFunction(newObj.submit)){
							newObj.init(cfg);
							return false;
						}else if(cfg._everyRead && isFunction(newObj.submit)){
							newObj.init(cfg);
							obj.modSubmit(window[mn+"_" + obj.flowId]);
							return false;
						}else{
							obj.modSubmit(newObj);
							return false;
						}
					}
					
					window[mn+"_" + obj.flowId] = cloneClass(arguments[0]);
					window[mn+"_" + obj.flowId].init(cfg);
					
					// ����ǵ���amsSubmit
					if (isFunction(obj.modSubmit)){
						obj.modSubmit(window[mn+"_" + obj.flowId]);
					}
				});
			}
		});
	}
	
	function submit(obj){
		
		// ���ģ��submit���� 
		obj.modSubmit = function(modObj){
			if(isFunction(modObj.submit)){
				modObj.submit(obj.flowId);
			}
		};
		
		init(obj);	
	}
	
	extend( milo.ams, {
		/**
		 * ��ȡams�ĳ�ʼ��Ϣ
		 * @param {number} amsActivityId ���
		 * @param {function} callback �ص�����
		 * @return {undefined}
		 */
		
		amsInit : function(amsActivityId,flowId,callback){
		
			if(arguments.length === 1){
				init(amsActivityId); // amsActivityId ʵ������һ��object
				return;
			}
		
			getAmsFile(amsActivityId,flowId, function(ams_actdesc){
				var flows = ams_actdesc.flows,
					flow = null,
					cfg = window["amsCfg_" + flowId] || {};			
				
				for(fid in flows){
					if (fid == "f_" + flowId){
						flow = flows[fid];
						break
					}
				}
				
				if (flow == null) return;
				
				//�����ж�
				//���ڴ˽��У���Ӱ��ǰ�ڲ���Ҫ�ύֻ��Ҫչʾ�Ĺ���			
				//���Կ�������չʾ��ģ����ύ��ģ��
				cfg.iAMSActivityId = amsActivityId;
				cfg.iFlowId = flowId;
				
				if(flow.functions[0].sExtModuleId == null){
					//����
					if(amsActivityId == 7163){
						need("ams.flowengine_poker",function(FlowEngine){
							FlowEngine.submit(window['amsCfg_'+flowId]);
						});
					}else{
						need("ams.flowengine",function(FlowEngine){
							FlowEngine.submit(window['amsCfg_'+flowId]);
						});
					}
				}else{
					var modName = flow.functions[0].method;
					
					if(modName ==  'share.microblogFix' || modName ==  'share.microblogUser' || modName ==  'share.qqgameFeed' || modName ==  'share.qqSignButton' || modName ==  'share.qqSignQueryTime' || modName ==  'share.qqSignRadio'  || modName ==  'share.qzoneFix'  || modName ==  'share.qzoneUser' || modName ==  'share.shareQueryHistory'){
						flow.functions[0].method = 'share.commShare';
					}
					
					// ��������
					if(modName == 'cdkey.cdkeyExchage' &&  amsActivityId == 8814){
						flow.functions[0].method = 'cdkey.cdkeyExchage_02';
					}
				
					need("ams." + flow.functions[0].method, function(){
						var module = flow.functions[0].method.split("."),
							mn = module[module.length-1],
							newObj = window[mn+"_"+flowId];
						
						if(isObject(newObj) && isFunction(callback)){
							if(!isFunction(newObj.submit)){
								newObj.init(cfg, flow);
								return false;
							}else if(cfg._everyRead && isFunction(newObj.submit)){
								newObj.init(cfg, flow);
								callback(window[mn+"_"+flowId]);
								return false;
							}else{
								callback(newObj);
								return false;
							}
							
						}
						
						window[mn+"_"+flowId] = cloneClass(arguments[0]);
						window[mn+"_"+flowId].init(cfg, flow);

						if (isFunction(callback)){
							callback(window[mn+"_"+flowId]);
						}
					});
				}		
			});	
			
		},
		/**
		 * ��ȡams�ĳ�ʼ��Ϣ
		 * @param {number} amsActivityId ���
		 * @param {function} callback �ص�����
		 * @return {undefined}
		 */
		amsSubmit : function(amsActivityId, flowId){
		
			if(arguments.length === 1){
				submit(amsActivityId); // amsActivityId ʵ������һ��object
				return;
			}
		
			//��ȡ����Ԫ����action-data���Դ����window["amsCfg_" + flowId].triggerSourceData
			var caller = arguments.callee.caller;
			if((window.event && window.event.srcElement && window.event.srcElement != document) || (caller && caller.arguments[0])){
				var ev = window.event || caller.arguments[0];
				if(ev[0]){
					var target = ev[0];
				}else{
					var target = ev.srcElement || ev.target;
				}
				if(target){
					var data = (target.getAttribute && target.getAttribute('action-data')) || {}
						_amsCFG = window["amsCfg_" + flowId];
					
					try{
						_amsCFG.triggerSourceData = eval("(" + data + ")");
					}catch(e){
						_amsCFG.triggerSourceData = data;
					}
				}
			}
		
			amsInit(amsActivityId,flowId, function(obj){
				if(isFunction(obj.submit)){
					obj.submit(flowId);
				}
			});	
		}
	});
})();

milo.base.extend(window, milo.ams); 

namespace("milo.ui");

(function(){
	/**
	 * milo.ui��ʼ��Ϣ
	 * @param {msg} strgin ��Ҫ�������ַ�������
	 * @return {undefined}
	 */
	extend( milo.ui, {
		'alert' : function(msg){
			alert(msg);
		}
	});
})();

String.prototype.replaceAll = function (s1, s2) {
    return this.replace(new RegExp(s1,"gm"),s2);
}/*  |xGv00|695abf3f6dd5cef383324c7b9b393bb2 */