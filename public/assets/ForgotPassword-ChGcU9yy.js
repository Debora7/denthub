import{T as u,o as r,c,w as m,a,u as t,Z as d,f as p,t as f,g as _,b as s,d as g,n as b,e as x}from"./app-m8O9x8db.js";import{_ as v}from"./GuestLayout-CR49ia2E.js";import{_ as y}from"./InputError-mYeCkuLg.js";import{_ as k}from"./InputLabel-vZ1nCw19.js";import{P as w}from"./PrimaryButton-D-IF3Vpv.js";import{_ as V}from"./TextInput-CdSXe7W9.js";import"./ApplicationLogo-L9kXl_zd.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const B=s("div",{class:"mb-4 text-sm text-gray-600"}," Ai uitat parola? Nicio problemă. Spune-ne adresa ta de email și îți vom trimite un link de resetare a parolei care îți va permite să alegi una nouă. ",-1),N={key:0,class:"mb-4 font-medium text-sm text-green-600"},h={class:"flex items-center justify-end mt-4"},z={__name:"ForgotPassword",props:{status:{type:String}},setup(o){const e=u({email:""}),l=()=>{e.post(route("password.email"))};return(P,i)=>(r(),c(v,null,{default:m(()=>[a(t(d),{title:"Forgot Password"}),B,o.status?(r(),p("div",N,f(o.status),1)):_("",!0),s("form",{onSubmit:x(l,["prevent"])},[s("div",null,[a(k,{for:"email",value:"Email"}),a(V,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:t(e).email,"onUpdate:modelValue":i[0]||(i[0]=n=>t(e).email=n),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),a(y,{class:"mt-2",message:t(e).errors.email},null,8,["message"])]),s("div",h,[a(w,{class:b({"opacity-25":t(e).processing}),disabled:t(e).processing},{default:m(()=>[g(" Trimite Linkul ")]),_:1},8,["class","disabled"])])],32)]),_:1}))}};export{z as default};