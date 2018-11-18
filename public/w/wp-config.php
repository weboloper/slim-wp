<?php
/**
 * WordPress için taban ayar dosyası.
 *
 * Bu dosya şu ayarları içerir: MySQL ayarları, tablo öneki,
 * gizli anahtaralr ve ABSPATH. Daha fazla bilgi için 
 * {@link https://codex.wordpress.org/Editing_wp-config.php wp-config.php düzenleme}
 * yardım sayfasına göz atabilirsiniz. MySQL ayarlarınızı servis sağlayıcınızdan edinebilirsiniz.
 *
 * Bu dosya kurulum sırasında wp-config.php dosyasının oluşturulabilmesi için
 * kullanılır. İsterseniz bu dosyayı kopyalayıp, ismini "wp-config.php" olarak değiştirip,
 * değerleri girerek de kullanabilirsiniz.
 *
 * @package WordPress
 */

// ** MySQL ayarları - Bu bilgileri sunucunuzdan alabilirsiniz ** //
/** WordPress için kullanılacak veritabanının adı */
define('DB_NAME', 'slim_wp');

/** MySQL veritabanı kullanıcısı */
define('DB_USER', 'root');

/** MySQL veritabanı parolası */
define('DB_PASSWORD', '');

/** MySQL sunucusu */
define('DB_HOST', 'localhost');

/** Yaratılacak tablolar için veritabanı karakter seti. */
define('DB_CHARSET', 'utf8mb4');

/** Veritabanı karşılaştırma tipi. Herhangi bir şüpheniz varsa bu değeri değiştirmeyin. */
define('DB_COLLATE', '');

/**#@+
 * Eşsiz doğrulama anahtarları.
 *
 * Her anahtar farklı bir karakter kümesi olmalı!
 * {@link http://api.wordpress.org/secret-key/1.1/salt WordPress.org secret-key service} servisini kullanarak yaratabilirsiniz.
 * Çerezleri geçersiz kılmak için istediğiniz zaman bu değerleri değiştirebilirsiniz. Bu tüm kullanıcıların tekrar giriş yapmasını gerektirecektir.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '[eNi4=-O6vQShS%:_$1M$[Yk3#vExDsKZfq;@~rE+#U2sX9Rv1:=~)U^<#@HIy}|');
define('SECURE_AUTH_KEY',  'qxEMxe6}[2/j&CVL6nj62jAFh?7`w_fimhm#3T**]!/v-hPz6nLrU::v?uJ4)j=W');
define('LOGGED_IN_KEY',    ')Qx(6HG%}Z6(}FK^OvW+60Ruf+[cuYN)`BEHWX=:a$ H6J?}3DiL{7DYylI78CHU');
define('NONCE_KEY',        '%XM4(6[dv2H{Us.<0i7r,Z$0UO%:JERGI26,1&T%{9R;|$=S2nvoXDf<?AdNl I=');
define('AUTH_SALT',        'AJ[HF./QG`YU[E+]AQOs*}4H)sOo.JFp_a+xKRMTp08^9T,vXb(HA)^>LZ.u9v%}');
define('SECURE_AUTH_SALT', 'Sp!Uq%gC2cn<$` ~(?V`K+;HWOn5mcF_Pu.@`y`jFBKE~C%d,&>Ny=ppo@pi/pE`');
define('LOGGED_IN_SALT',   '2T}e*&=>z+m<mkEqO]Q=_fSfWvMn!L9+&LKbrEpGT WVA C (CyHI8C$19gZt<X6');
define('NONCE_SALT',       '!TsM>wq%EPb=-Y2EN}J$puAu#h]2i]WA#J)j~>X(Dc~Wx_c^-7%/r4-s2P6X.`Ck');
/**#@-*/

/**
 * WordPress veritabanı tablo ön eki.
 *
 * Tüm kurulumlara ayrı bir önek vererek bir veritabanına birden fazla kurulum yapabilirsiniz.
 * Sadece rakamlar, harfler ve alt çizgi lütfen.
 */
$table_prefix  = 'wp_';

/**
 * Geliştiriciler için: WordPress hata ayıklama modu.
 *
 * Bu değeri "true" yaparak geliştirme sırasında hataların ekrana basılmasını sağlayabilirsiniz.
 * Tema ve eklenti geliştiricilerinin geliştirme aşamasında WP_DEBUG
 * kullanmalarını önemle tavsiye ederiz.
 */
define('WP_DEBUG', false);

/* Hepsi bu kadar. Mutlu bloglamalar! */

/** WordPress dizini için mutlak yol. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** WordPress değişkenlerini ve yollarını kurar. */
require_once(ABSPATH . 'wp-settings.php');
