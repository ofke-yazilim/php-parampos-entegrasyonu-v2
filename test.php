<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
  font-family: Arial;
  font-size: 17px;
  padding: 8px;
}

* {
  box-sizing: border-box;
}

.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
}

.col-25,
.col-50,
.col-75 {
  padding: 0 16px;
}

.container {
  background-color: #f2f2f2;
  padding: 5px 20px 15px 20px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}

input[type=text] {
  width: 100%;
  margin-bottom: 5px;
  padding: 4px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

label {
  margin-bottom: 3px;
  display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.btn {
  background-color: #4CAF50;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.btn:hover {
  background-color: #45a049;
}

a {
  color: #2196F3;
}

hr {
  border: 1px solid lightgrey;
}

span.price {
  float: right;
  color: grey;
}

/* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
@media (max-width: 800px) {
  .row {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
</style>
</head>
<body>

<div class="row">
  <div class="">
    <div class="container" style="float:left;">
        <form action="/parampos-v2/index.php" method="POST">
      
        <div class="row">
          <div class="col-50">
            <h3>ÖDEME</h3>
            <label for="cname">Ödeme Tutarı</label>
            <input type="text" id="tutar" name="-Islem_Tutar-" placeholder="Ödeme Tutarı" value="100" required/>
            <label for="cname">Kart Sahibinin Adı</label>
            <input type="text" id="cname" name="-KK_Sahibi-" placeholder="Kart Sahibinin Adı" value="hakan" required/>
            <label for="ccnum">Kredi Kart Numarası</label>
            <input type="text" id="ccnum" name="-KK_No-" placeholder="Kredi Kart Numarası" required/>
            <label for="expmonth">Son Kullanım Ay</label>
            <input type="text" id="expmonth" name="-KK_SK_Ay-" placeholder="12" value="12" required/>
            <div class="row">
              <div class="col-50">
                <label for="expyear">Son Kullanım Year</label>
                <input type="text" id="expyear" name="-KK_SK_Yil-" placeholder="2020" value="2026" required/>
              </div>
              <div class="col-50">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="-KK_CVC-" placeholder="000" value="000" required/>
              </div>
            </div>
            <div class="oranlar">
            </div>
          </div>
          
        </div>
        <input type="hidden" id="pos_id" name="pos_id" >
        <input type="hidden" id="total" name="total" >
        <input type="submit" value="Onayla" id="btn" class="btn" style="background-color: #dddddd">
      </form>
	  
    </div>
		  <pre style="float:left;">
	TEST KARTLARI 
	
	ZİRAAT BANKASI
	Kart Numarası (Visa): 4546711234567894
	Kart Numarası (Master Card): 5401341234567891
	Son Kullanma Tarihi: 12/26
	Güvenlik Numarası: 000
	Kart 3D Secure Şifresi: a

	FİNANSBANK
	Kart Numarası (Visa): 4022774022774026
	Kart Numarası (Master Card): 5456165456165454
	Son Kullanma Tarihi: 12/26
	Güvenlik Numarası: 000
	Kart 3D Secure Şifresi: a

	AKBANK
	Kart Numarası (Visa): 4355084355084358
	Kart Numarası (Master Card): 5571135571135575
	Son Kullanma Tarihi: 12/26
	Güvenlik Numarası: 000
	Kart 3D Secure Şifresi: a
	  </pre>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

document.getElementById("btn").disabled = true;
$(document).ready(function(){


    $(document).on("click",".cpos_id",function() {
        var $total = $(this).attr('total');
        $('#total').val($total);
    });

    $('#ccnum').keyup(function(e){
        if(e.key == "Backspace"){
            var _length = $(this).val().length;
            if(_length < 6){
                document.getElementById("btn").disabled = false;
                $('.btn').css("background-color","#dddddd");
            }
        }
    });

  $("#ccnum").keyup(function(){
    var _length = $(this).val().length;
    var _bin    = $(this).val();
    
    var tutar   = $("#tutar").val();
    if(!parseInt(tutar)>0){
        alert("Öncelikle Tutarı Girniz.");
        return false;
    }
    
    if(_length == 6){
		$.ajax({
			url : '/parampos-v2/bin.php?bin='+_bin+"&tutar="+tutar,
			type: 'POST',
			data: "",
			dataType: 'json',
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(json) {
			   console.log(json);
			   $(".oranlar").html(json.table);
			   $("#pos_id").val(json.pos_id);
                document.getElementById("btn").disabled = false;
                $('.btn').removeAttr('style');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
    }
    
  });
  
  $("#ccnum").bind("paste", function(e){
	var _length = $(this).val().length;
    var _bin    = e.originalEvent.clipboardData.getData('text');
	
    var tutar   = $("#tutar").val();
    if(!parseInt(tutar)>0){
        alert("Öncelikle Tutarı Girniz.");
        return false;
    }
	
	_bin = _bin.trim();
	_bin = _bin.substr(0,6);
	
	_length = _bin.length;
	
	if(_length>5){
		$.ajax({
			url : '/parampos-v2/bin.php?bin='+_bin+"&tutar="+tutar,
			type: 'POST',
			data: "",
			dataType: 'json',
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(json) {
			   console.log(json);
			   $(".oranlar").html(json.table);
			   $("#pos_id").val(json.pos_id);
                document.getElementById("btn").disabled = false;
                $('.btn').removeAttr('style');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

	}
} );
});
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-52346402-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-52346402-1');
</script>
</body>
</html>
