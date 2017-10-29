function $1(id){
   return document.getElementById(id);
}
   function sureopr(val){
		 if(!window.confirm('确定'+val+'吗?')){
		    return false;
		 }
	 }

 function allchecked(currentobj,cbname){
     var  checkboxs = document.getElementsByName(cbname); 
	 //alert(checkboxs.length);
	    for (var i=0;i<checkboxs.length ;i++ )
	    {
			if(currentobj.checked){
			
			  checkboxs[i].checked = true;
			}else{
			  checkboxs[i].checked = false;
			}
	    }
	 
 }
 function myfocus(){
	 this.style.backgroundColor = '#ccc';
	 switch (this.name)
	 {
		case 'goods_name': gn.innerText = '*';gn.style.color = 'red';break;
		case 'shop_price': sp.innerText = '*';sp.style.color = 'red';;break;
		case 'goods_weight': gw.innerText = '*';gw.style.color = 'red';break;
		case 'goods_number':  gnum.innerText = '*';gnum.style.color = 'red';break;
	 }
 }
 function myblur(){
    this.style.backgroundColor = '#fff';
    checkform(this);
 }
function checkform(currentobj){
         switch (currentobj.name)
         {
			 case 'goods_name':
					 if(currentobj.value == ''){
								currentobj.focus();
								gn.innerText = '您还没有填商品名';
								$1('flag').value = 0;
								return ;
					  }else{
							gn.innerText = '';
					  }
					  break;
			 case 'shop_price':
				     if(currentobj.value == ''){
						        currentobj.focus();
								sp.innerText ='您还没有填售价';
								$1('flag').value = 0;
								return ;
					 }else if(currentobj.value <= 0){
							   currentobj.focus();
								sp.innerText = '售价不能是0元以下';
								$1('flag').value = 0;
								return ;
					 }else{
							sp.innerText = '';
					 }
					 break;
			 case 'goods_weight':
				  if(currentobj.value == ''){
						        currentobj.focus();
								gw.innerText = '您还没有填重量';
								$1('flag').value = 0;
								return ;
					 }else if(currentobj.value <=  0){
							   currentobj.focus();
								gw.innerText = '重量不能是0以下';
								$1('flag').value = 0;
								return ;
					 }else{
							gw.innerText = '';
					 }
					 break;
			 case  'goods_number':
				  if(currentobj.value == ''){
						        currentobj.focus();
								gnum.innerText ='您还没有填库存量';
								$1('flag').value = 0;
								return ;
					 }else if(currentobj.value <= 0){
							   currentobj.focus();
							  gnum.innerText = '库存量必须大于0';
							  $1('flag').value = 0;
								return ;
					 }else{
							gnum.innerText = '';
					 }	
					 break;
			 case 'goods_brief':
				 if(currentobj.value.length <10 || currentobj.value.length > 100){
							    currentobj.focus();
								gb.innerText = '简述在10到100个字符';
								$1('flag').value = 0;
								return ;
			     }
				 break;
         
         }
}