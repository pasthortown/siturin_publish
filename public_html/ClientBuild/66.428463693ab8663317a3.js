(window.webpackJsonp=window.webpackJsonp||[]).push([[66],{B9Yq:function(l,n){l.exports=function(){throw new Error("define cannot be used indirect")}},JEAp:function(l,n,t){var u,e=e||function(l){"use strict";if(!(void 0===l||"undefined"!=typeof navigator&&/MSIE [1-9]\./.test(navigator.userAgent))){var n=function(){return l.URL||l.webkitURL||l},t=l.document.createElementNS("http://www.w3.org/1999/xhtml","a"),u="download"in t,e=/constructor/i.test(l.HTMLElement)||l.safari,o=/CriOS\/[\d]+/.test(navigator.userAgent),a=function(n){(l.setImmediate||l.setTimeout)(function(){throw n},0)},r=function(l){setTimeout(function(){"string"==typeof l?n().revokeObjectURL(l):l.remove()},4e4)},i=function(l){return/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(l.type)?new Blob([String.fromCharCode(65279),l],{type:l.type}):l},c=function(c,s,d){d||(c=i(c));var p,f=this,g="application/octet-stream"===c.type,v=function(){!function(l,n,t){for(var u=(n=[].concat(n)).length;u--;){var e=l["on"+n[u]];if("function"==typeof e)try{e.call(l,l)}catch(o){a(o)}}}(f,"writestart progress write writeend".split(" "))};if(f.readyState=f.INIT,u)return p=n().createObjectURL(c),void setTimeout(function(){var l,n;t.href=p,t.download=s,l=t,n=new MouseEvent("click"),l.dispatchEvent(n),v(),r(p),f.readyState=f.DONE});!function(){if((o||g&&e)&&l.FileReader){var t=new FileReader;return t.onloadend=function(){var n=o?t.result:t.result.replace(/^data:[^;]*;/,"data:attachment/file;");l.open(n,"_blank")||(l.location.href=n),n=void 0,f.readyState=f.DONE,v()},t.readAsDataURL(c),void(f.readyState=f.INIT)}p||(p=n().createObjectURL(c)),g?l.location.href=p:l.open(p,"_blank")||(l.location.href=p),f.readyState=f.DONE,v(),r(p)}()},s=c.prototype;return"undefined"!=typeof navigator&&navigator.msSaveOrOpenBlob?function(l,n,t){return n=n||l.name||"download",t||(l=i(l)),navigator.msSaveOrOpenBlob(l,n)}:(s.abort=function(){},s.readyState=s.INIT=0,s.WRITING=1,s.DONE=2,s.error=s.onwritestart=s.onprogress=s.onwrite=s.onabort=s.onerror=s.onwriteend=null,function(l,n,t){return new c(l,n||l.name||"download",t)})}}("undefined"!=typeof self&&self||"undefined"!=typeof window&&window||this.content);l.exports?l.exports.saveAs=e:null!==t("B9Yq")&&null!==t("PDX0")&&(void 0===(u=(function(){return e}).call(n,t,n,l))||(l.exports=u))},PDX0:function(l,n){(function(n){l.exports=n}).call(this,{})},aNIi:function(l,n,t){"use strict";t.r(n);var u=t("CcnG"),e=function(){return function(){}}(),o=t("pMnS"),a=t("Ip0R"),r=t("gIcY"),i=t("JEAp"),c=t("2VFn"),s=function(){return function(){}}(),d=function(){function l(l,n,t){this.modalService=l,this.toastr=n,this.approvalDataService=t,this.approvals=[],this.approvalSelected=new s,this.currentPage=1,this.lastPage=1,this.showDialog=!1,this.recordsByPage=5}return l.prototype.ngOnInit=function(){this.goToPage(1)},l.prototype.selectApproval=function(l){this.approvalSelected=l},l.prototype.goToPage=function(l){l<1||l>this.lastPage?this.toastr.errorToastr("La p\xe1gina solicitada no existe.","Error"):(this.currentPage=l,this.getApprovals())},l.prototype.getApprovals=function(){var l=this;this.approvals=[],this.approvalSelected=new s,this.approvalDataService.get_paginate(this.recordsByPage,this.currentPage).then(function(n){l.approvals=n.data,l.lastPage=n.last_page}).catch(function(l){return console.log(l)})},l.prototype.newApproval=function(l){this.approvalSelected=new s,this.openDialog(l)},l.prototype.editApproval=function(l){void 0!==this.approvalSelected.id?this.openDialog(l):this.toastr.errorToastr("Debe seleccionar un registro.","Error")},l.prototype.deleteApproval=function(){var l=this;void 0!==this.approvalSelected.id?this.approvalDataService.delete(this.approvalSelected.id).then(function(n){l.toastr.successToastr("Registro Borrado satisfactoriamente.","Borrar"),l.getApprovals()}).catch(function(l){return console.log(l)}):this.toastr.errorToastr("Debe seleccionar un registro.","Error")},l.prototype.backup=function(){this.approvalDataService.getBackUp().then(function(l){var n=new Blob([JSON.stringify(l)],{type:"text/plain"}),t=new Date;Object(i.saveAs)(n,t.toLocaleDateString()+"_Approvals.json")}).catch(function(l){return console.log(l)})},l.prototype.toCSV=function(){this.approvalDataService.get().then(function(l){var n="id;name\n";l.forEach(function(l){n+=l.id+";"+l.name+"\n"});var t=new Blob(["\ufeff",n],{type:"text/plain"}),u=new Date;Object(i.saveAs)(t,u.toLocaleDateString()+"_Approvals.csv")}).catch(function(l){return console.log(l)})},l.prototype.decodeUploadFile=function(l){var n=this,t=new FileReader;l.target.files&&l.target.files.length>0&&(t.readAsDataURL(l.target.files[0]),t.onload=function(){var l=t.result.toString().split(",")[1],u=JSON.parse(decodeURIComponent(escape(atob(l))));n.approvalDataService.masiveLoad(u).then(function(l){n.goToPage(n.currentPage)}).catch(function(l){return console.log(l)})})},l.prototype.openDialog=function(l){var n=this;this.modalService.open(l,{centered:!0}).result.then(function(l){"Guardar click"===l&&(void 0===n.approvalSelected.id?n.approvalDataService.post(n.approvalSelected).then(function(l){n.toastr.successToastr("Datos guardados satisfactoriamente.","Nuevo"),n.getApprovals()}).catch(function(l){return console.log(l)}):n.approvalDataService.put(n.approvalSelected).then(function(l){n.toastr.successToastr("Registro actualizado satisfactoriamente.","Actualizar"),n.getApprovals()}).catch(function(l){return console.log(l)}))},function(l){})},l}(),p=t("4GxJ"),f=t("3EpR"),g=u["\u0275crt"]({encapsulation:0,styles:[[""]],data:{}});function v(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,0,"span",[["class","far fa-hand-point-right"]],null,null,null,null,null))],null,null)}function m(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,5,"tr",[],null,[[null,"click"]],function(l,n,t){var u=!0;return"click"===n&&(u=!1!==l.component.selectApproval(l.context.$implicit)&&u),u},null,null)),(l()(),u["\u0275eld"](1,0,null,null,2,"td",[["class","text-right"]],null,null,null,null,null)),(l()(),u["\u0275and"](16777216,null,null,1,null,v)),u["\u0275did"](3,16384,null,0,a.NgIf,[u.ViewContainerRef,u.TemplateRef],{ngIf:[0,"ngIf"]},null),(l()(),u["\u0275eld"](4,0,null,null,1,"td",[],null,null,null,null,null)),(l()(),u["\u0275ted"](5,null,["",""]))],function(l,n){l(n,3,0,n.component.approvalSelected===n.context.$implicit)},function(l,n){l(n,5,0,n.context.$implicit.name)})}function b(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,1,"button",[["class","btn btn-light"],["disabled",""],["title","Primera P\xe1gina"],["type","button"]],null,null,null,null,null)),(l()(),u["\u0275ted"](-1,null,["Primera"]))],null,null)}function h(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,1,"button",[["class","btn btn-light"],["title","Primera P\xe1gina"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0;return"click"===n&&(u=!1!==l.component.goToPage(1)&&u),u},null,null)),(l()(),u["\u0275ted"](-1,null,["Primera"]))],null,null)}function y(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,1,"button",[["class","btn btn-light"],["title","P\xe1gina Anterior"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0,e=l.component;return"click"===n&&(u=!1!==e.goToPage(1*e.currentPage-1)&&u),u},null,null)),(l()(),u["\u0275ted"](1,null,["",""]))],null,function(l,n){l(n,1,0,1*n.component.currentPage-1)})}function w(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,1,"button",[["class","btn btn-light"],["title","P\xe1gina Siguiente"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0,e=l.component;return"click"===n&&(u=!1!==e.goToPage(1*e.currentPage+1)&&u),u},null,null)),(l()(),u["\u0275ted"](1,null,["",""]))],null,function(l,n){l(n,1,0,1*n.component.currentPage+1)})}function P(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,1,"button",[["class","btn btn-light"],["title","\xdaltima P\xe1gina"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0,e=l.component;return"click"===n&&(u=!1!==e.goToPage(e.lastPage)&&u),u},null,null)),(l()(),u["\u0275ted"](-1,null,["\xdaltima"]))],null,null)}function k(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,1,"button",[["class","btn btn-light"],["disabled",""],["title","\xdaltima P\xe1gina"],["type","button"]],null,null,null,null,null)),(l()(),u["\u0275ted"](-1,null,["\xdaltima"]))],null,null)}function S(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,5,"div",[["class","modal-header"]],null,null,null,null,null)),(l()(),u["\u0275eld"](1,0,null,null,1,"h4",[["class","modal-title"]],null,null,null,null,null)),(l()(),u["\u0275ted"](-1,null,["Datos:"])),(l()(),u["\u0275eld"](3,0,null,null,2,"button",[["class","close"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0;return"click"===n&&(u=!1!==l.context.$implicit.dismiss("Cross click")&&u),u},null,null)),(l()(),u["\u0275eld"](4,0,null,null,1,"span",[],null,null,null,null,null)),(l()(),u["\u0275ted"](-1,null,["\xd7"])),(l()(),u["\u0275eld"](6,0,null,null,12,"div",[["class","modal-body"]],null,null,null,null,null)),(l()(),u["\u0275eld"](7,0,null,null,11,"div",[["class","row"]],null,null,null,null,null)),(l()(),u["\u0275eld"](8,0,null,null,10,"div",[["class","col-12"]],null,null,null,null,null)),(l()(),u["\u0275eld"](9,0,null,null,9,"div",[["class","form-group row"]],null,null,null,null,null)),(l()(),u["\u0275eld"](10,0,null,null,1,"label",[["class","col-4 col-form-label"],["for","name"]],null,null,null,null,null)),(l()(),u["\u0275ted"](-1,null,["name"])),(l()(),u["\u0275eld"](12,0,null,null,6,"div",[["class","col-8"]],null,null,null,null,null)),(l()(),u["\u0275eld"](13,0,null,null,5,"input",[["class","form-control"],["id","name"],["name","name"],["placeholder","name"],["type","text"]],[[2,"ng-untouched",null],[2,"ng-touched",null],[2,"ng-pristine",null],[2,"ng-dirty",null],[2,"ng-valid",null],[2,"ng-invalid",null],[2,"ng-pending",null]],[[null,"ngModelChange"],[null,"input"],[null,"blur"],[null,"compositionstart"],[null,"compositionend"]],function(l,n,t){var e=!0,o=l.component;return"input"===n&&(e=!1!==u["\u0275nov"](l,14)._handleInput(t.target.value)&&e),"blur"===n&&(e=!1!==u["\u0275nov"](l,14).onTouched()&&e),"compositionstart"===n&&(e=!1!==u["\u0275nov"](l,14)._compositionStart()&&e),"compositionend"===n&&(e=!1!==u["\u0275nov"](l,14)._compositionEnd(t.target.value)&&e),"ngModelChange"===n&&(e=!1!==(o.approvalSelected.name=t)&&e),e},null,null)),u["\u0275did"](14,16384,null,0,r.d,[u.Renderer2,u.ElementRef,[2,r.a]],null,null),u["\u0275prd"](1024,null,r.h,function(l){return[l]},[r.d]),u["\u0275did"](16,671744,null,0,r.m,[[8,null],[8,null],[8,null],[6,r.h]],{name:[0,"name"],model:[1,"model"]},{update:"ngModelChange"}),u["\u0275prd"](2048,null,r.i,null,[r.m]),u["\u0275did"](18,16384,null,0,r.j,[[4,r.i]],null,null),(l()(),u["\u0275eld"](19,0,null,null,4,"div",[["class","modal-footer"]],null,null,null,null,null)),(l()(),u["\u0275eld"](20,0,null,null,1,"button",[["class","btn btn-outline-success"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0;return"click"===n&&(u=!1!==l.context.$implicit.close("Guardar click")&&u),u},null,null)),(l()(),u["\u0275ted"](-1,null,["Guardar"])),(l()(),u["\u0275eld"](22,0,null,null,1,"button",[["class","btn btn-outline-danger"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0;return"click"===n&&(u=!1!==l.context.$implicit.close("Cancelar click")&&u),u},null,null)),(l()(),u["\u0275ted"](-1,null,["Cancelar"]))],function(l,n){l(n,16,0,"name",n.component.approvalSelected.name)},function(l,n){l(n,13,0,u["\u0275nov"](n,18).ngClassUntouched,u["\u0275nov"](n,18).ngClassTouched,u["\u0275nov"](n,18).ngClassPristine,u["\u0275nov"](n,18).ngClassDirty,u["\u0275nov"](n,18).ngClassValid,u["\u0275nov"](n,18).ngClassInvalid,u["\u0275nov"](n,18).ngClassPending)})}function I(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,2,"div",[["class","row"]],null,null,null,null,null)),(l()(),u["\u0275eld"](1,0,null,null,1,"h1",[["class","col-12 text-right"]],null,null,null,null,null)),(l()(),u["\u0275ted"](-1,null,[" Approval "])),(l()(),u["\u0275eld"](3,0,null,null,21,"div",[["class","row"]],null,null,null,null,null)),(l()(),u["\u0275eld"](4,0,null,null,20,"div",[["class","col-12"]],null,null,null,null,null)),(l()(),u["\u0275eld"](5,0,null,null,19,"div",[["class","btn-toolbar"],["role","toolbar"]],null,null,null,null,null)),(l()(),u["\u0275eld"](6,0,null,null,2,"div",[["class","btn-group mr-2"],["role","group"]],null,null,null,null,null)),(l()(),u["\u0275eld"](7,0,null,null,1,"button",[["class","btn btn-primary"],["title","Actualizar"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0,e=l.component;return"click"===n&&(u=!1!==e.goToPage(e.currentPage)&&u),u},null,null)),(l()(),u["\u0275eld"](8,0,null,null,0,"i",[["class","fas fa-sync"]],null,null,null,null,null)),(l()(),u["\u0275eld"](9,0,null,null,4,"div",[["class","btn-group mr-2"],["role","group"]],null,null,null,null,null)),(l()(),u["\u0275eld"](10,0,null,null,1,"button",[["class","btn btn-success"],["title","Nuevo"],["type","button"]],null,[[null,"click"]],function(l,n,t){var e=!0;return"click"===n&&(e=!1!==l.component.newApproval(u["\u0275nov"](l,60))&&e),e},null,null)),(l()(),u["\u0275eld"](11,0,null,null,0,"i",[["class","fas fa-file"]],null,null,null,null,null)),(l()(),u["\u0275eld"](12,0,null,null,1,"button",[["class","btn btn-warning"],["title","Editar"],["type","button"]],null,[[null,"click"]],function(l,n,t){var e=!0;return"click"===n&&(e=!1!==l.component.editApproval(u["\u0275nov"](l,60))&&e),e},null,null)),(l()(),u["\u0275eld"](13,0,null,null,0,"i",[["class","fas fa-edit"]],null,null,null,null,null)),(l()(),u["\u0275eld"](14,0,null,null,2,"div",[["class","btn-group mr-2"],["role","group"]],null,null,null,null,null)),(l()(),u["\u0275eld"](15,0,null,null,1,"button",[["class","btn btn-danger"],["title","Eliminar"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0;return"click"===n&&(u=!1!==l.component.deleteApproval()&&u),u},null,null)),(l()(),u["\u0275eld"](16,0,null,null,0,"i",[["class","fas fa-trash"]],null,null,null,null,null)),(l()(),u["\u0275eld"](17,0,null,null,7,"div",[["class","btn-group mr-2"],["role","group"]],null,null,null,null,null)),(l()(),u["\u0275eld"](18,0,null,null,1,"button",[["class","btn btn-dark"],["title","BackUp"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0;return"click"===n&&(u=!1!==l.component.backup()&&u),u},null,null)),(l()(),u["\u0275eld"](19,0,null,null,0,"i",[["class","fas fa-download"]],null,null,null,null,null)),(l()(),u["\u0275eld"](20,0,null,null,1,"button",[["class","btn btn-dark"],["title","Exportar CSV"],["type","button"]],null,[[null,"click"]],function(l,n,t){var u=!0;return"click"===n&&(u=!1!==l.component.toCSV()&&u),u},null,null)),(l()(),u["\u0275eld"](21,0,null,null,0,"i",[["class","fas fa-file-csv"]],null,null,null,null,null)),(l()(),u["\u0275eld"](22,0,null,null,1,"button",[["class","btn btn-dark"],["title","Cargar"],["type","button"]],null,[[null,"click"]],function(l,n,t){var e=!0;return"click"===n&&(e=!1!==u["\u0275nov"](l,24).click()&&e),e},null,null)),(l()(),u["\u0275eld"](23,0,null,null,0,"i",[["class","fas fa-upload"]],null,null,null,null,null)),(l()(),u["\u0275eld"](24,0,[["uploadInput",1]],null,0,"input",[["accept",".json"],["class","form-control"],["type","file"]],[[8,"hidden",0]],[[null,"change"]],function(l,n,t){var u=!0;return"change"===n&&(u=!1!==l.component.decodeUploadFile(t)&&u),u},null,null)),(l()(),u["\u0275eld"](25,0,null,null,11,"div",[["class","row"]],null,null,null,null,null)),(l()(),u["\u0275eld"](26,0,null,null,10,"div",[["class","col-12"]],null,null,null,null,null)),(l()(),u["\u0275eld"](27,0,null,null,9,"table",[["class","table table-hover mt-2"]],null,null,null,null,null)),(l()(),u["\u0275eld"](28,0,null,null,5,"thead",[],null,null,null,null,null)),(l()(),u["\u0275eld"](29,0,null,null,4,"tr",[],null,null,null,null,null)),(l()(),u["\u0275eld"](30,0,null,null,1,"th",[],null,null,null,null,null)),(l()(),u["\u0275ted"](-1,null,["Seleccionado"])),(l()(),u["\u0275eld"](32,0,null,null,1,"th",[],null,null,null,null,null)),(l()(),u["\u0275ted"](-1,null,["name"])),(l()(),u["\u0275eld"](34,0,null,null,2,"tbody",[],null,null,null,null,null)),(l()(),u["\u0275and"](16777216,null,null,1,null,m)),u["\u0275did"](36,278528,null,0,a.NgForOf,[u.ViewContainerRef,u.TemplateRef,u.IterableDiffers],{ngForOf:[0,"ngForOf"]},null),(l()(),u["\u0275eld"](37,0,null,null,22,"div",[["class","row"]],null,null,null,null,null)),(l()(),u["\u0275eld"](38,0,null,null,21,"div",[["class","col-12"]],null,null,null,null,null)),(l()(),u["\u0275eld"](39,0,null,null,20,"div",[["class","btn-toolbar"],["role","toolbar"]],null,null,null,null,null)),(l()(),u["\u0275eld"](40,0,null,null,14,"div",[["class","btn-group mr-2"],["role","group"]],null,null,null,null,null)),(l()(),u["\u0275and"](16777216,null,null,1,null,b)),u["\u0275did"](42,16384,null,0,a.NgIf,[u.ViewContainerRef,u.TemplateRef],{ngIf:[0,"ngIf"]},null),(l()(),u["\u0275and"](16777216,null,null,1,null,h)),u["\u0275did"](44,16384,null,0,a.NgIf,[u.ViewContainerRef,u.TemplateRef],{ngIf:[0,"ngIf"]},null),(l()(),u["\u0275and"](16777216,null,null,1,null,y)),u["\u0275did"](46,16384,null,0,a.NgIf,[u.ViewContainerRef,u.TemplateRef],{ngIf:[0,"ngIf"]},null),(l()(),u["\u0275eld"](47,0,null,null,1,"button",[["class","btn btn-primary"],["title","P\xe1gina Actual"],["type","button"]],null,null,null,null,null)),(l()(),u["\u0275ted"](48,null,["",""])),(l()(),u["\u0275and"](16777216,null,null,1,null,w)),u["\u0275did"](50,16384,null,0,a.NgIf,[u.ViewContainerRef,u.TemplateRef],{ngIf:[0,"ngIf"]},null),(l()(),u["\u0275and"](16777216,null,null,1,null,P)),u["\u0275did"](52,16384,null,0,a.NgIf,[u.ViewContainerRef,u.TemplateRef],{ngIf:[0,"ngIf"]},null),(l()(),u["\u0275and"](16777216,null,null,1,null,k)),u["\u0275did"](54,16384,null,0,a.NgIf,[u.ViewContainerRef,u.TemplateRef],{ngIf:[0,"ngIf"]},null),(l()(),u["\u0275eld"](55,0,null,null,4,"div",[["class","input-group"]],null,null,null,null,null)),(l()(),u["\u0275eld"](56,0,null,null,2,"div",[["class","input-group-prepend"]],null,null,null,null,null)),(l()(),u["\u0275eld"](57,0,null,null,1,"button",[["class","input-group-text btn btn-success"],["title","Ir a la P\xe1gina"],["type","button"]],null,[[null,"click"]],function(l,n,t){var e=!0;return"click"===n&&(e=!1!==l.component.goToPage(u["\u0275nov"](l,59).value)&&e),e},null,null)),(l()(),u["\u0275ted"](-1,null,["Ir a"])),(l()(),u["\u0275eld"](59,0,[["goToPageNumber",1]],null,0,"input",[["class","form-control"],["placeholder","Ir a la P\xe1gina"],["type","number"]],[[8,"min",0],[8,"max",0]],null,null,null,null)),(l()(),u["\u0275and"](0,[["content",2]],null,0,null,S))],function(l,n){var t=n.component;l(n,36,0,t.approvals),l(n,42,0,1===t.currentPage),l(n,44,0,1!==t.currentPage),l(n,46,0,t.currentPage>1),l(n,50,0,t.currentPage<t.lastPage),l(n,52,0,t.currentPage!==t.lastPage),l(n,54,0,t.currentPage===t.lastPage)},function(l,n){var t=n.component;l(n,24,0,!0),l(n,48,0,t.currentPage),l(n,59,0,u["\u0275inlineInterpolate"](1,"",1,""),u["\u0275inlineInterpolate"](1,"",t.lastPage,""))})}function C(l){return u["\u0275vid"](0,[(l()(),u["\u0275eld"](0,0,null,null,1,"app-approval",[],null,null,null,I,g)),u["\u0275did"](1,114688,null,0,d,[p.y,f.a,c.a],null,null)],function(l,n){l(n,1,0)},null)}var R=u["\u0275ccf"]("app-approval",d,C,{},{},[]),T=t("sE5F"),A=t("ZYCi"),D=function(){return function(){}}();t.d(n,"ApprovalModuleNgFactory",function(){return N});var N=u["\u0275cmf"](e,[],function(l){return u["\u0275mod"]([u["\u0275mpd"](512,u.ComponentFactoryResolver,u["\u0275CodegenComponentFactoryResolver"],[[8,[o.a,R]],[3,u.ComponentFactoryResolver],u.NgModuleRef]),u["\u0275mpd"](4608,a.NgLocalization,a.NgLocaleLocalization,[u.LOCALE_ID,[2,a["\u0275angular_packages_common_common_a"]]]),u["\u0275mpd"](4608,r.s,r.s,[]),u["\u0275mpd"](4608,p.y,p.y,[u.ComponentFactoryResolver,u.Injector,p.tb,p.z]),u["\u0275mpd"](4608,c.a,c.a,[T.e,A.l]),u["\u0275mpd"](1073742336,a.CommonModule,a.CommonModule,[]),u["\u0275mpd"](1073742336,A.o,A.o,[[2,A.u],[2,A.l]]),u["\u0275mpd"](1073742336,D,D,[]),u["\u0275mpd"](1073742336,r.p,r.p,[]),u["\u0275mpd"](1073742336,r.e,r.e,[]),u["\u0275mpd"](1073742336,e,e,[]),u["\u0275mpd"](1024,A.j,function(){return[[{path:"",component:d}]]},[])])})}}]);