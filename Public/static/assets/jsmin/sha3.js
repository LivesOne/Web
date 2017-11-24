!function(){"use strict";function a(a,b,c){this.blocks=[],this.s=[],this.padding=b,this.outputBits=c,this.reset=!0,this.block=0,this.start=0,this.blockCount=1600-(a<<1)>>5,this.byteCount=this.blockCount<<2,this.outputBlocks=c>>5,this.extraBytes=(31&c)>>3;for(var d=0;50>d;++d)this.s[d]=0}function b(b,c,d){a.call(this,b,c,d)}var c="object"==typeof window?window:{},d=!c.JS_SHA3_NO_NODE_JS&&"object"==typeof process&&process.versions&&process.versions.node;d&&(c=global);var e=!c.JS_SHA3_NO_COMMON_JS&&"object"==typeof module&&module.exports,f=!c.JS_SHA3_NO_ARRAY_BUFFER&&"undefined"!=typeof ArrayBuffer,g="0123456789abcdef".split(""),h=[31,7936,2031616,520093696],i=[4,1024,262144,67108864],j=[1,256,65536,16777216],k=[6,1536,393216,100663296],l=[0,8,16,24],m=[1,0,32898,0,32906,2147483648,2147516416,2147483648,32907,0,2147483649,0,2147516545,2147483648,32777,2147483648,138,0,136,0,2147516425,0,2147483658,0,2147516555,0,139,2147483648,32905,2147483648,32771,2147483648,32770,2147483648,128,2147483648,32778,0,2147483658,2147483648,2147516545,2147483648,32896,2147483648,2147483649,0,2147516424,2147483648],n=[224,256,384,512],o=[128,256],p=["hex","buffer","arrayBuffer","array"],q={128:168,256:136};(c.JS_SHA3_NO_NODE_JS||!Array.isArray)&&(Array.isArray=function(a){return"[object Array]"===Object.prototype.toString.call(a)});for(var r=function(b,c,d){return function(e){return new a(b,c,b).update(e)[d]()}},s=function(b,c,d){return function(e,f){return new a(b,c,f).update(e)[d]()}},t=function(a,b,c){return function(b,d,e,f){return B["cshake"+a].update(b,d,e,f)[c]()}},u=function(a,b,c){return function(b,d,e,f){return B["kmac"+a].update(b,d,e,f)[c]()}},v=function(a,b,c,d){for(var e=0;e<p.length;++e){var f=p[e];a[f]=b(c,d,f)}return a},w=function(b,c){var d=r(b,c,"hex");return d.create=function(){return new a(b,c,b)},d.update=function(a){return d.create().update(a)},v(d,r,b,c)},x=function(b,c){var d=s(b,c,"hex");return d.create=function(d){return new a(b,c,d)},d.update=function(a,b){return d.create(b).update(a)},v(d,s,b,c)},y=function(b,c){var d=q[b],e=t(b,c,"hex");return e.create=function(e,f,g){return f||g?new a(b,c,e).bytepad([f,g],d):B["shake"+b].create(e)},e.update=function(a,b,c,d){return e.create(b,c,d).update(a)},v(e,t,b,c)},z=function(a,c){var d=q[a],e=u(a,c,"hex");return e.create=function(e,f,g){return new b(a,c,f).bytepad(["KMAC",g],d).bytepad([e],d)},e.update=function(a,b,c,d){return e.create(a,c,d).update(b)},v(e,u,a,c)},A=[{name:"keccak",padding:j,bits:n,createMethod:w},{name:"sha3",padding:k,bits:n,createMethod:w},{name:"shake",padding:h,bits:o,createMethod:x},{name:"cshake",padding:i,bits:o,createMethod:y},{name:"kmac",padding:i,bits:o,createMethod:z}],B={},C=[],D=0;D<A.length;++D)for(var E=A[D],F=E.bits,G=0;G<F.length;++G){var H=E.name+"_"+F[G];if(C.push(H),B[H]=E.createMethod(F[G],E.padding),"sha3"!==E.name){var I=E.name+F[G];C.push(I),B[I]=B[H]}}a.prototype.update=function(a){var b="string"!=typeof a;b&&a.constructor===c.ArrayBuffer&&(a=new Uint8Array(a));var d=a.length;if(b&&("number"!=typeof d||!(Array.isArray(a)||f&&ArrayBuffer.isView(a))))throw"input is invalid type";for(var e,g,h=this.blocks,i=this.byteCount,j=this.blockCount,k=0,m=this.s;d>k;){if(this.reset)for(this.reset=!1,h[0]=this.block,e=1;j+1>e;++e)h[e]=0;if(b)for(e=this.start;d>k&&i>e;++k)h[e>>2]|=a[k]<<l[3&e++];else for(e=this.start;d>k&&i>e;++k)g=a.charCodeAt(k),128>g?h[e>>2]|=g<<l[3&e++]:2048>g?(h[e>>2]|=(192|g>>6)<<l[3&e++],h[e>>2]|=(128|63&g)<<l[3&e++]):55296>g||g>=57344?(h[e>>2]|=(224|g>>12)<<l[3&e++],h[e>>2]|=(128|g>>6&63)<<l[3&e++],h[e>>2]|=(128|63&g)<<l[3&e++]):(g=65536+((1023&g)<<10|1023&a.charCodeAt(++k)),h[e>>2]|=(240|g>>18)<<l[3&e++],h[e>>2]|=(128|g>>12&63)<<l[3&e++],h[e>>2]|=(128|g>>6&63)<<l[3&e++],h[e>>2]|=(128|63&g)<<l[3&e++]);if(this.lastByteIndex=e,e>=i){for(this.start=e-i,this.block=h[j],e=0;j>e;++e)m[e]^=h[e];J(m),this.reset=!0}else this.start=e}return this},a.prototype.encode=function(a,b){var c=255&a,d=1,e=[c];for(a>>=8,c=255&a;c>0;)e.unshift(c),a>>=8,c=255&a,++d;return b?e.push(d):e.unshift(d),this.update(e),e.length},a.prototype.encodeString=function(a){a=a||"";var b="string"!=typeof a;b&&a.constructor===c.ArrayBuffer&&(a=new Uint8Array(a));var d=a.length;if(b&&("number"!=typeof d||!(Array.isArray(a)||f&&ArrayBuffer.isView(a))))throw"input is invalid type";var e=0;if(b)e=d;else for(var g=0;g<a.length;++g){var h=a.charCodeAt(g);128>h?e+=1:2048>h?e+=2:55296>h||h>=57344?e+=3:(h=65536+((1023&h)<<10|1023&a.charCodeAt(++g)),e+=4)}return e+=this.encode(8*e),this.update(a),e},a.prototype.bytepad=function(a,b){for(var c=this.encode(b),d=0;d<a.length;++d)c+=this.encodeString(a[d]);var e=b-c%b,f=[];return f.length=e,this.update(f),this},a.prototype.finalize=function(){var a=this.blocks,b=this.lastByteIndex,c=this.blockCount,d=this.s;if(a[b>>2]|=this.padding[3&b],this.lastByteIndex===this.byteCount)for(a[0]=a[c],b=1;c+1>b;++b)a[b]=0;for(a[c-1]|=2147483648,b=0;c>b;++b)d[b]^=a[b];J(d)},a.prototype.toString=a.prototype.hex=function(){this.finalize();for(var a,b=this.blockCount,c=this.s,d=this.outputBlocks,e=this.extraBytes,f=0,h=0,i="";d>h;){for(f=0;b>f&&d>h;++f,++h)a=c[f],i+=g[a>>4&15]+g[15&a]+g[a>>12&15]+g[a>>8&15]+g[a>>20&15]+g[a>>16&15]+g[a>>28&15]+g[a>>24&15];h%b===0&&(J(c),f=0)}return e&&(a=c[f],e>0&&(i+=g[a>>4&15]+g[15&a]),e>1&&(i+=g[a>>12&15]+g[a>>8&15]),e>2&&(i+=g[a>>20&15]+g[a>>16&15])),i},a.prototype.arrayBuffer=function(){this.finalize();var a,b=this.blockCount,c=this.s,d=this.outputBlocks,e=this.extraBytes,f=0,g=0,h=this.outputBits>>3;a=new ArrayBuffer(e?d+1<<2:h);for(var i=new Uint32Array(a);d>g;){for(f=0;b>f&&d>g;++f,++g)i[g]=c[f];g%b===0&&J(c)}return e&&(i[f]=c[f],a=a.slice(0,h)),a},a.prototype.buffer=a.prototype.arrayBuffer,a.prototype.digest=a.prototype.array=function(){this.finalize();for(var a,b,c=this.blockCount,d=this.s,e=this.outputBlocks,f=this.extraBytes,g=0,h=0,i=[];e>h;){for(g=0;c>g&&e>h;++g,++h)a=h<<2,b=d[g],i[a]=255&b,i[a+1]=b>>8&255,i[a+2]=b>>16&255,i[a+3]=b>>24&255;h%c===0&&J(d)}return f&&(a=h<<2,b=d[g],f>0&&(i[a]=255&b),f>1&&(i[a+1]=b>>8&255),f>2&&(i[a+2]=b>>16&255)),i},b.prototype=new a,b.prototype.finalize=function(){return this.encode(this.outputBits,!0),a.prototype.finalize.call(this)};var J=function(a){var b,c,d,e,f,g,h,i,j,k,l,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,$,_,aa,ba,ca,da,ea,fa,ga,ha,ia,ja,ka;for(d=0;48>d;d+=2)e=a[0]^a[10]^a[20]^a[30]^a[40],f=a[1]^a[11]^a[21]^a[31]^a[41],g=a[2]^a[12]^a[22]^a[32]^a[42],h=a[3]^a[13]^a[23]^a[33]^a[43],i=a[4]^a[14]^a[24]^a[34]^a[44],j=a[5]^a[15]^a[25]^a[35]^a[45],k=a[6]^a[16]^a[26]^a[36]^a[46],l=a[7]^a[17]^a[27]^a[37]^a[47],n=a[8]^a[18]^a[28]^a[38]^a[48],o=a[9]^a[19]^a[29]^a[39]^a[49],b=n^(g<<1|h>>>31),c=o^(h<<1|g>>>31),a[0]^=b,a[1]^=c,a[10]^=b,a[11]^=c,a[20]^=b,a[21]^=c,a[30]^=b,a[31]^=c,a[40]^=b,a[41]^=c,b=e^(i<<1|j>>>31),c=f^(j<<1|i>>>31),a[2]^=b,a[3]^=c,a[12]^=b,a[13]^=c,a[22]^=b,a[23]^=c,a[32]^=b,a[33]^=c,a[42]^=b,a[43]^=c,b=g^(k<<1|l>>>31),c=h^(l<<1|k>>>31),a[4]^=b,a[5]^=c,a[14]^=b,a[15]^=c,a[24]^=b,a[25]^=c,a[34]^=b,a[35]^=c,a[44]^=b,a[45]^=c,b=i^(n<<1|o>>>31),c=j^(o<<1|n>>>31),a[6]^=b,a[7]^=c,a[16]^=b,a[17]^=c,a[26]^=b,a[27]^=c,a[36]^=b,a[37]^=c,a[46]^=b,a[47]^=c,b=k^(e<<1|f>>>31),c=l^(f<<1|e>>>31),a[8]^=b,a[9]^=c,a[18]^=b,a[19]^=c,a[28]^=b,a[29]^=c,a[38]^=b,a[39]^=c,a[48]^=b,a[49]^=c,p=a[0],q=a[1],V=a[11]<<4|a[10]>>>28,W=a[10]<<4|a[11]>>>28,D=a[20]<<3|a[21]>>>29,E=a[21]<<3|a[20]>>>29,ha=a[31]<<9|a[30]>>>23,ia=a[30]<<9|a[31]>>>23,R=a[40]<<18|a[41]>>>14,S=a[41]<<18|a[40]>>>14,J=a[2]<<1|a[3]>>>31,K=a[3]<<1|a[2]>>>31,r=a[13]<<12|a[12]>>>20,s=a[12]<<12|a[13]>>>20,X=a[22]<<10|a[23]>>>22,Y=a[23]<<10|a[22]>>>22,F=a[33]<<13|a[32]>>>19,G=a[32]<<13|a[33]>>>19,ja=a[42]<<2|a[43]>>>30,ka=a[43]<<2|a[42]>>>30,ba=a[5]<<30|a[4]>>>2,ca=a[4]<<30|a[5]>>>2,L=a[14]<<6|a[15]>>>26,M=a[15]<<6|a[14]>>>26,t=a[25]<<11|a[24]>>>21,u=a[24]<<11|a[25]>>>21,Z=a[34]<<15|a[35]>>>17,$=a[35]<<15|a[34]>>>17,H=a[45]<<29|a[44]>>>3,I=a[44]<<29|a[45]>>>3,z=a[6]<<28|a[7]>>>4,A=a[7]<<28|a[6]>>>4,da=a[17]<<23|a[16]>>>9,ea=a[16]<<23|a[17]>>>9,N=a[26]<<25|a[27]>>>7,O=a[27]<<25|a[26]>>>7,v=a[36]<<21|a[37]>>>11,w=a[37]<<21|a[36]>>>11,_=a[47]<<24|a[46]>>>8,aa=a[46]<<24|a[47]>>>8,T=a[8]<<27|a[9]>>>5,U=a[9]<<27|a[8]>>>5,B=a[18]<<20|a[19]>>>12,C=a[19]<<20|a[18]>>>12,fa=a[29]<<7|a[28]>>>25,ga=a[28]<<7|a[29]>>>25,P=a[38]<<8|a[39]>>>24,Q=a[39]<<8|a[38]>>>24,x=a[48]<<14|a[49]>>>18,y=a[49]<<14|a[48]>>>18,a[0]=p^~r&t,a[1]=q^~s&u,a[10]=z^~B&D,a[11]=A^~C&E,a[20]=J^~L&N,a[21]=K^~M&O,a[30]=T^~V&X,a[31]=U^~W&Y,a[40]=ba^~da&fa,a[41]=ca^~ea&ga,a[2]=r^~t&v,a[3]=s^~u&w,a[12]=B^~D&F,a[13]=C^~E&G,a[22]=L^~N&P,a[23]=M^~O&Q,a[32]=V^~X&Z,a[33]=W^~Y&$,a[42]=da^~fa&ha,a[43]=ea^~ga&ia,a[4]=t^~v&x,a[5]=u^~w&y,a[14]=D^~F&H,a[15]=E^~G&I,a[24]=N^~P&R,a[25]=O^~Q&S,a[34]=X^~Z&_,a[35]=Y^~$&aa,a[44]=fa^~ha&ja,a[45]=ga^~ia&ka,a[6]=v^~x&p,a[7]=w^~y&q,a[16]=F^~H&z,a[17]=G^~I&A,a[26]=P^~R&J,a[27]=Q^~S&K,a[36]=Z^~_&T,a[37]=$^~aa&U,a[46]=ha^~ja&ba,a[47]=ia^~ka&ca,a[8]=x^~p&r,a[9]=y^~q&s,a[18]=H^~z&B,a[19]=I^~A&C,a[28]=R^~J&L,a[29]=S^~K&M,a[38]=_^~T&V,a[39]=aa^~U&W,a[48]=ja^~ba&da,a[49]=ka^~ca&ea,a[0]^=m[d],a[1]^=m[d+1]};if(e)module.exports=B;else for(var D=0;D<C.length;++D)c[C[D]]=B[C[D]]}();