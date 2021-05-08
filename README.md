## Param Entegrasyonu V2 ##
Bu proje PHP yazılım dili ve 
<a href='https://dev.param.com.tr/tr/api'>https://dev.param.com.tr/tr/api</a> 
dökümantasyonu kullanılarak kodlandı.

#### Konfigirasyon Tanımlamaları
 - İşletmeye ait olan tanımlamalar. **`Config/index.php`** dosyası üzerinde tanımlanmalıdır.<br>
 - *`Config/index.php`* dosyası içerisinde tanımlı olarak bulunan `WHICH`(7.satır) parametresi iki değer alabiliyor.
 eğer test üzerinde işlem yürütmek istiyorsanız `TEST`, 
 canlı servis adresi üzerinde çalışmak istiyorsanız `PROD` olarak tanımlamalısınız.
 - Eğer canlı yani PROD ortamda çalışmak istiyorsanız.  *`Config/index.php`* sayfasına aşağıdaki örneğe benzer bir datayı `POST` 
 methodu ile göndermelisiniz.
 <pre>
 $_POST        = [
     '-KK_Sahibi-'          => 'okesmez',
     '-KK_No-'              => '4022774022774026',
     '-KK_SK_Ay-'           => 12,
     '-KK_SK_Yil-'          => 26,
     '-KK_CVC-'             => '000',
     '-KK_Sahibi_GSM-'      => '',
     '-Siparis_ID-'         => time(),
     '-Siparis_Aciklama-'   => 'Test Siparişi',
     '-Taksit-'             => 1, //1,2,3,5....
     '-Islem_Tutar-'        => '12,00',
     '-Toplam_Tutar-'       => '12,00',
     '-Islem_ID-'           => 'islemId'.time(),
     '-IPAdr-'              => $_SERVER['REMOTE_ADDR'],
     '-1a-'                 => 'Test Data 1',
     '-2a-'                 => 'Test Data 2',
     '-3a-'                 => 'Test Data 3',
 ];
 </pre>
 - Parampos kullanılarak yapılan ödeme işlemlerinin başarılı ve başarısız tamamlanamsı durumuna göre 
 yönlendirileceği adresleri yine *`Config/index.php`* dosyasında tanımlamalısınız.(30 ve 31. satırlar)
 
 
 #### Örnek 3D PAY(Ödeme) örneği
 - 3D Pay ile ödemeye ait örnek çalışan kodlar için <a target='_blank' href='https://github.com/ofke-yazilim/parampos-v2/blob/master/index.php'>https://github.com/ofke-yazilim/parampos-v2/blob/master/index.php</a>
 dosyasınI inceleyebilirsiniz.
 
 #### Örnek Kullanıcı Özel Pos Oranlarının Alınması
 - Özel Taksit oranlarının çekildiği örnek çalışan kodlar için <a target='_blank' href='https://github.com/ofke-yazilim/parampos-v2/blob/master/installment.php'>https://github.com/ofke-yazilim/parampos-v2/blob/master/installment.php</a> dosyasını inceleyebilirisiniz.
 
## Yeni Servis Entegrasyonu Eklemek
- Xml dizini içerisine, kullanılacak soap xml dosyası eklenir.(Örneğin 3D Pay için <a target='_blank' href='https://dev.param.com.tr/tr/api/oedeme-v2'>https://dev.param.com.tr/tr/api/oedeme-v2</a> adresinde bulunan tab menu içerisindeki `Request` sekmesinde bulunan xml verisi alınır.)
- Builder dizini içerisine gidilerek: class içerisine eklenmiş olan XML prametrelerini içerecek bir array tanımlanır. Array içerisindeki değerler yukarıda bahsettiğim linkte bulunan `Gönderilecek Parametreler` sekmesinde mevcuttur. Ardından tanımlanan bu array'ı return olarak döndürecek fonksiyon tanımlanır.
- Request dizinine girerek en üst kısma Servise ait endpoint tanımlaması yapılır. Parampos üzerinde çalışan tüm servis adreslerine ve endpointlerine 
<a target='_blank' href='https://test-dmz.param.com.tr:4443/turkpos.ws/service_turkpos_test.asmx'>https://test-dmz.param.com.tr:4443/turkpos.ws/service_turkpos_test.asmx</a> adresinden ilgili methodlara tıklayarak ulaşabilirsiniz. Endpoint verileri açmış olduğunuz sayfada `?` ve sonrasında tanımlı olan değerlerdir. Örneğin **`?op=Pos_Odeme`** gibi.
- Request içerisinde tanımladığınız değer ayrıca servisi çağırdığınız zaman kullanacağınız `type` değerini de temsil eder.
- Request dizininde ayrıca sevise istek atacağımız xml verisini hazırlayacak bir fonksiyon ekliyoruz. 
Bu fonksiyon _**Builder**_ içerisinden hazırlanan parametreleri:  _**XML**_ içerisinde tanımlamış olduğumuz xml dosyasında belirtilen uygun yerlere koyarak ilgili request xml verisini hazırlayacaktır.
- Response içerisinde: Request içerisinde tanımlana endpoint ile aynı isimde bir fonksiton tanımlaması yapılacak. Bu fonksiyon servisten gelen xml verisinii parse edecek şekilde düzenlenecek. 
- Ardından aşağıda bulunan örnek kodlara uygun olarak ilgili servis verileri çağrılabilir.
<pre>
    // Turn off error reporting
    error_reporting(0);
    
    require_once 'Config/index.php'; //Konfigirasyon tanımlamaları alınıyor
    require_once 'Request/Request.php'; //Request için xml hazırlayan class ekleniyor
    require_once 'Builder/Builder.php'; // İstek parametrelerini array olarak ayarlar
    
    $request = new Request();
    $builder = new Builder();
    
    $data = []; //Request parametrelerini saklıyor.
    
    /**
     * Aşağıdaki hazırlanacak yeni servisin çalıştırılmasın sağlayan fonksiyonların çağrılması şeklini içerir.
     */
    $parameters = $builder-><strong>BURAYA BUILDER İÇERİSİNDE TANIMLANAN FONKSİYON İSMİ GELECEK</strong>();
    
    //Servise gönderilecek olan xml içeriği hazırlanıyor
    $request_xml = $request-><strong>BURAYA REQUEST İÇERİSİNDE TANIMLANAN FONKSİYON İSMİ GELECEK</strong>($parameters);
    
    //Servis çağrılıyor ve Response verisi alınıyor
    $response = $request->sendRequest($request_xml, '<strong>BURAYA REQUEST İÇERİSİNDE ENDPOINT İÇİN TANIMLANAN DEĞER GELECEK</strong>');
    
</pre>
