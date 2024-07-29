import{T as k,o as l,f as n,a,u as t,w as u,F as _,Z as h,b as s,e as v,q as y,n as D,d as V,i as m,v as M,t as N,m as f,g as U}from"./app-m8O9x8db.js";import{_ as S}from"./AuthenticatedLayout-oP7uY6wx.js";import{_ as p}from"./InputError-mYeCkuLg.js";import{_ as b}from"./InputLabel-vZ1nCw19.js";import{P as B}from"./PrimaryButton-D-IF3Vpv.js";import{_ as C}from"./TextInput-CdSXe7W9.js";import{t as $}from"./toastr.min-sOBkje4z.js";import"./ApplicationLogo-L9kXl_zd.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const z={class:"py-12"},L={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},P={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},T={class:"p-6 text-gray-900"},q={class:"mt-4"},E={class:"flex items-center"},F=["id","onUpdate:modelValue"],J=["for"],j={key:0,class:"flex mt-2 space-x-4"},O=["onUpdate:modelValue"],R=["onUpdate:modelValue"],W={class:"flex items-center justify-end mt-4"},te={__name:"NewMedic",setup(Z){const e=k({doctor:"",workingDays:{Luni:{enabled:!1,start_time:"",end_time:""},Marți:{enabled:!1,start_time:"",end_time:""},Miercuri:{enabled:!1,start_time:"",end_time:""},Joi:{enabled:!1,start_time:"",end_time:""},Vineri:{enabled:!1,start_time:"",end_time:""},Sâmbătă:{enabled:!1,start_time:"",end_time:""},Duminică:{enabled:!1,start_time:"",end_time:""}},errors:{doctor:"",workingDays:""}}),g=["Luni","Marți","Miercuri","Joi","Vineri","Sâmbătă","Duminică"],w=()=>{e.post(route("consult.medic.store"),{onSuccess:()=>{e.reset(),d("Medicul a fost salvat")},onError:()=>{d("Rezolvă problemele înainte de a salva.","error")}})},d=(c,r="success")=>{$[r](c)};return(c,r)=>(l(),n(_,null,[a(t(h),{title:"Medic nou"}),a(S,null,{default:u(()=>[s("div",z,[s("div",L,[s("div",P,[s("div",T,[s("form",{onSubmit:v(w,["prevent"])},[s("div",null,[a(b,{for:"doctor",value:"Nume și prenume",required:!0}),a(C,{id:"doctor",type:"text",class:"mt-1 block w-full",modelValue:t(e).doctor,"onUpdate:modelValue":r[0]||(r[0]=o=>t(e).doctor=o),autofocus:""},null,8,["modelValue"]),a(p,{class:"mt-2",message:t(e).errors.doctor},null,8,["message"])]),s("div",q,[a(b,{for:"workingDays",value:"Programul de lucru"}),(l(),n(_,null,y(g,(o,x)=>s("div",{key:x,class:"mt-4"},[s("div",E,[m(s("input",{type:"checkbox",id:o,"onUpdate:modelValue":i=>t(e).workingDays[o].enabled=i,class:"form-checkbox h-5 w-5 text-indigo-600"},null,8,F),[[M,t(e).workingDays[o].enabled]]),s("label",{for:o,class:"ml-3 block text-sm font-medium text-gray-700"},N(o),9,J)]),t(e).workingDays[o].enabled?(l(),n("div",j,[m(s("input",{type:"time","onUpdate:modelValue":i=>t(e).workingDays[o].start_time=i,class:"form-input block w-full sm:text-sm border-gray-300 rounded-md"},null,8,O),[[f,t(e).workingDays[o].start_time]]),m(s("input",{type:"time","onUpdate:modelValue":i=>t(e).workingDays[o].end_time=i,class:"form-input block w-full sm:text-sm border-gray-300 rounded-md"},null,8,R),[[f,t(e).workingDays[o].end_time]])])):U("",!0)])),64)),a(p,{class:"mt-2",message:t(e).errors.workingDays},null,8,["message"])]),s("div",W,[a(B,{class:D(["ms-4",{"opacity-25":t(e).processing}]),disabled:t(e).processing},{default:u(()=>[V(" Salvează ")]),_:1},8,["class","disabled"])])],32)])])])])]),_:1})],64))}};export{te as default};