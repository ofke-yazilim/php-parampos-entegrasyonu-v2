##Param Entegrasyonu V2
Bu proje PHP yazılım dili ve 
<a href='https://dev.param.com.tr/tr/api'>https://dev.param.com.tr/tr/api</a> 
dökümantasyonu kullanılarak kodlandı.

####Konfigirasyon Tanımlamaları
 - İşletmeye ait olan tanımlamalar. **`Config/index.php`** dosyası üzerinde tanımlanmalıdır.<br>
 - *`Config/index.php`* dosyası içerisinde tanımlı olarak bulunan `WHICH`(7.satır) parametresi iki değer alabiliyor.
 eğer test üzerinde işlem yürütmek istiyorsanız `TEST`, 
 canlı servis adresi üzerinde çalışmak istiyorsanız `PROD` olarak tanımlamalısınız.
 - Eğer canlı yani PROD ortamda çalışmak istiyorsanız.  *`Config/index.php`* dosyası içerisinde tanımlı olan kredş kart bilgilerinin 
 gerçek kredi kart bilgileri ile değiştirilmesi gerekmektedir.(36. ve 41. satır aralığı)
 - Parampos kullanılarak yapılan ödeme işlemlerinin başarılı ve başarısız tamamlanamsı durumuna göre 
 yönlendirileceği adresleri yine *`Config/index.php`* dosyasında tanımlamalısınız.(30 ve 31. satırlar)
 
 
 ####Örnek 3D PAY(Ödeme) örneği
 
 
