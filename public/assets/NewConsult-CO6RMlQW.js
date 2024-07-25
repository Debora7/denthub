import{T as q,r as z,k as C,o as d,f as u,a as o,u as l,w,F as p,Z as N,b as s,e as A,i as _,p as v,q as f,d as x,n as B,t as y}from"./app-m8O9x8db.js";import{_ as j}from"./AuthenticatedLayout-oP7uY6wx.js";import{_ as c}from"./InputError-mYeCkuLg.js";import{_ as n}from"./InputLabel-vZ1nCw19.js";import{P as D}from"./PrimaryButton-D-IF3Vpv.js";import{_ as V}from"./TextInput-CdSXe7W9.js";import{_ as M}from"./NumberInput-DhAg5uvU.js";import{_ as P}from"./TextareaInput-DQQc-ID_.js";import{t as T}from"./toastr.min-sOBkje4z.js";import"./ApplicationLogo-L9kXl_zd.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const E={class:"py-12"},F={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},J={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},L={class:"p-6 text-gray-900"},O=s("option",{value:""},"Selectează medicul",-1),R=["value"],Z={class:"flex space-x-4"},G={class:"flex-1"},H={class:"flex-1"},I={class:"flex-1"},K=["id","onUpdate:modelValue"],Q=["value"],W=s("i",{class:"fas fa-plus mr-2"},null,-1),X={class:"mt-4"},Y={class:"mt-4 flex space-x-4"},ee={class:"flex-1"},se=s("option",{value:""}," Selectează județul ",-1),te=["value"],oe={class:"flex-1"},le=s("option",{value:""}," Selectează orașul ",-1),re=["value"],ae={class:"flex items-center justify-end mt-4"},be={__name:"NewConsult",props:{status:{type:String},counties:Array,doctors:Array},setup(k){const b=k,t=q({doctor:"",address:"",city:"",county:"",services:[{service:"",price:0,description:"",time:""}]}),g=z([]),$=m=>{const r=b.counties.find(e=>e.id===m);g.value=r?r.cities:[]};C(()=>t.county,m=>{$(m)});const S=()=>{t.services.push({service:"",price:0,description:"",time:""})},U=()=>{t.post(route("consult.store"),{onSuccess:()=>{t.reset(),h("Serviciile au fost salvate")},onError:()=>{h("Rezolvă problemele înainte de a salva.","error")}})},h=(m,r="success")=>{T[r](m)};return(m,r)=>(d(),u(p,null,[o(l(N),{title:"Serviciu nou"}),o(j,null,{default:w(()=>[s("div",E,[s("div",F,[s("div",J,[s("div",L,[s("form",{onSubmit:A(U,["prevent"])},[s("div",null,[o(n,{for:"doctor",value:"Medic",required:!0}),_(s("select",{id:"doctor",class:"mt-1 block w-full","onUpdate:modelValue":r[0]||(r[0]=e=>l(t).doctor=e),style:{"border-radius":"5px","border-color":"lightgray"}},[O,(d(!0),u(p,null,f(b.doctors,e=>(d(),u("option",{key:e.id,value:e.id},y(e.name),9,R))),128))],512),[[v,l(t).doctor]]),o(c,{class:"mt-2",message:l(t).errors.doctor},null,8,["message"])]),(d(!0),u(p,null,f(l(t).services,(e,a)=>(d(),u("div",{key:a,class:"mt-4 flex flex-col space-y-4"},[s("div",Z,[s("div",G,[o(n,{for:`service-${a}`,value:"Serviciul oferit",required:!0},null,8,["for"]),o(V,{id:`service-${a}`,type:"text",class:"mt-1 block w-full",modelValue:e.service,"onUpdate:modelValue":i=>e.service=i},null,8,["id","modelValue","onUpdate:modelValue"]),o(c,{class:"mt-2",message:l(t).errors[`services.${a}.service`]},null,8,["message"])]),s("div",H,[o(n,{for:`price-${a}`,value:"Preț",required:!0},null,8,["for"]),o(M,{id:`price-${a}`,type:"number",step:"0.10",class:"mt-1 block w-full",modelValue:e.price,"onUpdate:modelValue":i=>e.price=i},null,8,["id","modelValue","onUpdate:modelValue"]),o(c,{class:"mt-2",message:l(t).errors[`services.${a}.price`]},null,8,["message"])]),s("div",I,[o(n,{for:`time-${a}`,value:"Timp (minute)",required:!0},null,8,["for"]),_(s("select",{id:`time-${a}`,class:"mt-1 block w-full","onUpdate:modelValue":i=>e.time=i,style:{"border-radius":"5px","border-color":"lightgray"}},[(d(),u(p,null,f(60,i=>s("option",{key:i,value:i},y(i),9,Q)),64))],8,K),[[v,e.time]]),o(c,{class:"mt-2",message:l(t).errors[`services.${a}.time`]},null,8,["message"])])]),s("div",null,[o(n,{for:`description-${a}`,value:"Descrierea serviciului",required:!0},null,8,["for"]),o(P,{id:`description-${a}`,type:"textarea",class:"mt-1 block w-full",modelValue:e.description,"onUpdate:modelValue":i=>e.description=i},null,8,["id","modelValue","onUpdate:modelValue"]),o(c,{class:"mt-2",message:l(t).errors[`services.${a}.description`]},null,8,["message"])])]))),128)),s("div",{class:"mt-4"},[s("button",{type:"button",onClick:S,style:{width:"100%"},class:"inline-flex items-center justify-center text-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-blue disabled:opacity-25 transition ease-in-out duration-150"},[W,x(" Adaugă Serviciu ")])]),s("div",X,[o(n,{for:"address",value:"Adresa completă",required:!0}),o(V,{id:"address",type:"text",class:"mt-1 block w-full",modelValue:l(t).address,"onUpdate:modelValue":r[1]||(r[1]=e=>l(t).address=e)},null,8,["modelValue"]),o(c,{class:"mt-2",message:l(t).errors.address},null,8,["message"])]),s("div",Y,[s("div",ee,[o(n,{for:"county",value:"Județ",required:!0}),_(s("select",{id:"county",class:"mt-1 block w-full","onUpdate:modelValue":r[2]||(r[2]=e=>l(t).county=e),style:{"border-radius":"5px","border-color":"lightgray"}},[se,(d(!0),u(p,null,f(b.counties,e=>(d(),u("option",{key:e.id,value:e.id},y(e.name),9,te))),128))],512),[[v,l(t).county]]),o(c,{class:"mt-2",message:l(t).errors.county},null,8,["message"])]),s("div",oe,[o(n,{for:"city",value:"Oraș",required:!0}),_(s("select",{id:"city",class:"mt-1 block w-full","onUpdate:modelValue":r[3]||(r[3]=e=>l(t).city=e),style:{"border-radius":"5px","border-color":"lightgray"}},[le,(d(!0),u(p,null,f(g.value,e=>(d(),u("option",{key:e.id,value:e.id},y(e.name),9,re))),128))],512),[[v,l(t).city]]),o(c,{class:"mt-2",message:l(t).errors.city},null,8,["message"])])]),s("div",ae,[o(D,{class:B(["ms-4",{"opacity-25":l(t).processing}]),disabled:l(t).processing},{default:w(()=>[x(" Salvează ")]),_:1},8,["class","disabled"])])],32)])])])])]),_:1})],64))}};export{be as default};
