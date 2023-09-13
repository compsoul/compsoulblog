/**
 ***********************************************************************************************
 *                             COMPSOUL BLOG MODULE - LEGAL NOTICE                             *
 ***********************************************************************************************
 * The Compsoul Blog module ("the Module") is the intellectual property of Daniel Dziedzic,
 * trading under the name "Compsoul". By downloading, installing, or using the Module, you
 * ("the User") agree to be bound by the following terms and conditions:
 *
 * 1. Modification and Customization:
 * The User is permitted to modify and customize the Module for their own use, provided that
 * any substantial modifications or alterations are made in consultation with the original
 * creator, Daniel Dziedzic. The User must seek approval for any significant changes to the Module.
 *
 * 2. Non-Profit Use:
 * The Module can be used by non-profit organizations without any restrictions, subject to
 * compliance with all other terms and conditions specified herein.
 *
 * 3. Prohibited Resale:
 * The User is strictly prohibited from selling, leasing, licensing, or distributing the Module
 * in any form, either as it is or in modified versions, for commercial or monetary gain. The Module
 * is intended for non-commercial use only.
 *
 * 4. Consultation with the Creator:
 * Any attempts to modify, alter, or distribute the Module, in part or in whole, must be communicated
 * and approved by the original creator, Daniel Dziedzic. For any inquiries or consultation, please
 * contact the creator via email at daniel@compsoul.pl.
 *
 * 5. Creator Information:
 * The Module has been created by Daniel Dziedzic, trading under the name "Compsoul". For more
 * information about the creator and their work, please visit the official website at compsoul.pl
 * (for Polish) or compsoul.dev (for English).
 *
 * 6. Display of Creator Information:
 * The User agrees to include a visible and accessible link to the creator's website, compsoul.pl or
 * compsoul.dev, at the bottom of the list of posts on the frontend of their website using the Module.
 * The link should be displayed prominently and must be maintained at all times.
 *
 * Contact:
 * For technical support or to obtain permission for Module modifications, please contact the
 * creator:
 *
 * @author Daniel Dziedzic <daniel@compsoul.pl>
 * @copyright Compsoul
 * @license Free License with Attribution
 * @link https://compsoul.pl
 * @link https://compsoul.dev
 *
 * The User acknowledges and agrees that any use of the Module is subject to the terms and conditions
 * outlined in this legal notice. Failure to comply with these terms may result in legal action.
 ***********************************************************************************************
 *                                     END OF LEGAL NOTICE                                     *
 ***********************************************************************************************
 */

class CompsoulBlog{get version(){return 0.5;}
constructor(selector){let _selector;switch(typeof selector){case"string":_selector=document.querySelectorAll(selector);break;case"object":_selector=(selector===null)?{}:(typeof selector.length==="undefined")?[selector]:selector;break;case"boolean":_selector={};break;case"undefined":_selector={};break;default:throw new TypeError(this.constructor.name+" - selector error: "+selector);}
this.length=_selector.length;Object.assign(this,_selector);}
addClass(classList){let len=this.length;while(len--){this[len].classList.add(...classList.split(" "));}
return this;}
ajax(settings){let request=new XMLHttpRequest(),type=settings.type,url=settings.url,data=settings.data,success=(settings.success)?settings.success:()=>{},error=(settings.error)?settings.error:()=>{};if(type==="GET"){if(!url)throw new TypeError("url error");request.open(type,url,true);request.onload=()=>{if(request.status>=200&&request.status<400){success(request.responseText);}else{console.warn("request error");error();}};request.onerror=()=>{error();};request.send();}else if(type==="POST"){if(!url)throw new TypeError("url error");if(!data)throw new TypeError("data error");request.open(type,url,true);request.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');request.send(data);}
return this;}
append(element){let len=this.length;while(len--){this[len].appendChild(element);}
return this;}
attr(attribute,value){let len=this.length;if(typeof value!=="undefined"){while(len--){this[len].setAttribute(attribute,value);}}else{return(this[0])?this[0].getAttribute(attribute):false;}
return this;}
each(callback){for(let i=0;i<this.length;i++){callback.apply(this[i]);}
return this;}
hasClass(className){return this[0].classList.contains(className);}
html(html){let len=this.length;if(typeof html!=="undefined"){while(len--){this[len].innerHTML=html;}}else{return this[0].innerHTML;}
return this;}
index(){let parent=(this[0])?this[0].parentElement:false,len=(this[0])?parent.children.length:false,index=-1;while(len--){if(parent.children[len]===this[0])index=len;}
return index;}
on(type,callback){let len=this.length;while(len--){this[len].addEventListener(type,callback);}
return this;}
off(eventName,eventHandler){let len=this.length;while(len--){this[len].removeEventListener(eventName,eventHandler);}
return this;}
remove(){let len=this.length;while(len--){if(this[len].parentNode)this[len].parentNode.removeChild(this[len]);}
return this;}
removeAttr(attribute){let len=this.length;while(len--){this[len].removeAttribute(attribute);}
return this;}
removeClass(classList){let len=this.length;while(len--){this[len].classList.remove(...classList.split(" "));}
return this;}
toggleClass(classList){let len=this.length;while(len--){this[len].classList.toggle(...classList.split(" "));}
return this;}}
