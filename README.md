# NALOGE #

### 1. naloga: ###

S pomočjo Twitter API-ja pridobi podatke za naslednje artiste:
* http://twitter.com/umek_1605
* https://twitter.com/katyperry
* https://twitter.com/ddlovato

Potrebno je pridobiti profilne podatke artista:
* followers,
* število vseh tweetov, ki jih je objavil artist,

ter podatke za posamezen tweet (pridobi le podatke za tweete, ki so bili objavljeni v januarju 2018):
* text,
* retweets,
* likes.

Naštete podatke je potrebno zapisati v MYSQL podatkovno bazo. Strukturo tabel prepuščam tebi.


### 2. naloga: ###

S pomočjo podatkov iz prve naloge pripravi dva tortna diagrama (uporabi c3 grafe http://c3js.org ). 
Prvi diagram naj prikazuje razmerje med artisti glede na followerje, drugi pa razmerje med artisti glede na seštevek pridobljenih vseh TWEET LIKE-ov. 


### 3. naloga: ###

Zgeneriraj dve tabeli s pomočjo podanih SQL stavkov:

CREATE TABLE `admin_group` (
  `id` int(11) NOT NULL,
  `display_name` varchar(255) COLLATE utf8_slovenian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

CREATE TABLE `admin_user_group` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

ter jih napolni s podatki:

INSERT INTO `admin_group` (`id`, `display_name`) VALUES
(2, 'KUCKO'),
(13, 'ULTIMATE'),
(14, 'MANAGMENT'),
(15, 'SUPER KUCKO'),
(20, 'KUCKO-FORMS'),
(22, 'KUCKO-BOOKING'),
(23, 'KUCKO-ICO'),
(24, 'DESIGN'),
(25, 'KUCKO-REWARDING'),
(26, 'MARKETING'),
(27, 'STATISTICS'),
(28, 'KUCKO - DSS'),
(29, 'KUCKO-Stats');

INSERT INTO `admin_user_group` (`user_id`, `group_id`) VALUES
(1, 13),
(3, 13),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(7, 22),
(8, 2),
(9, 15),
(9, 29),
(10, 15),
(10, 29),
(11, 14),
(11, 25),
(12, 14),
(13, 13),
(14, 14),
(15, 27),
(16, 2),
(16, 22),
(17, 2),
(17, 23),
(18, 2),
(19, 2),
(19, 22),
(20, 13)

Napiši poizvedbo, ki za določen user_id vrne vse group_id, ki jih uporabnik še nima zapisanih v tabeli admin_user_groups.

# OPIS REŠITVE #

### Kratek opis datotek ###

classes
 |- Artist.php ... objekt bo vseboval profilne podatke o Artistu
 |- ArtistList.php ... seznam Artistov iz baze ali TwiterAPI
 |- Tweet.php ...  objekt bo vseboval podatke o Tweetu
 |- TweetList.php  ... seznam tweetov iz baze ali TwiterAPI

js
 |- charts.js ... nastavitev pie chart diagrama knjižnice C3.js

libs
 |- twitterauth ... zunanja knjižnica za OAUTH avtorizacijo in pošiljanje REST zahtevkov

index.php
- prikazuje rešitev 1. in 2. naloge
- iz baze prebere artiste
- za vsakega artista izpiše št. tweetov, followerjev
- za vsakega artista izpiše seznam njegovih tweetov, ter retweets, likes 
- prikaže 2 tortna diagrama

update.php
- v bazo vpiše podatke o artistov (id, št. tweetov, followerjev)
- v bazo vpiše tweete artistov (omejeno na jan 2018 kot zahtevano)

chart-likes.php, chart-followers.php
- iz baze pridobita podatke za pie chart C3.js

api-credentials.php, db-config
- gesla, API ključi, podatki za povezavo z bazo

Opomba:
- Če imata dva uporabnika močno večje število folowerjev in vsoto likov, je procent tretjega pod 1% in se tega uporabnika na grafu sploh ne vidi.
- Aplikacija naloži le tweete meseca januarja (ker tako zahteva naloga)


### REŠITEV 3. naloge ###

V primeru podanega user_id = 7 bi bil ukaz:
SELECT * FROM admin_group WHERE id NOT IN (SELECT group_id FROM admin_user_group WHERE user_id = 7)
V priponki so skupine, ki jih uporabnik še nima vpisane v admin_user_groups.