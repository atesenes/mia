
# Abonelik Yenileme Sistemi

Mia Teknoloji için abonelik yenileme takip sistemidir.
İşlemler cron job kullanılarak gerçekleştirilmiştir.




## İş Akışı



### Üyelik Yenileme İşlemi
Her saatte bir defa kontrol işlemi başlatılıyor. O saat aralığı içinde üyeliği biten kullanıcılar için ödeme fonksiyonu çalıştırılıyor. Eğer ödeme başarılı ise, üyelik 1 ay uzatılıyor.
Başarısız ise, başarısız ödeme tablosuna ekleniyor



### Tekrar Ödeme İşlemi
Her saatte bir defa kontrol işlemi başlatılıyor. Bir gün önce o saat aralığı içinde başarısız ödeme yapılan abonelikler için tekrar ödeme fonksiyonu çalıştırılır. Eğer ödeme başarılı ise, üyelik 1 ay uzatılıyor.
Başarısız ise, başarısız ödeme tablosuna ekleniyor Başarısız ödeme tablosuna 2 defa kaydedildiği zaman üyelik iptal edilip kullanıcıya mail gönderiliyor.

## Akış şeması


<p align="center"><img src="/public/akis.png" alt="akis_semasi"></p>

