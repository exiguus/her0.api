# her0 API
API that serves IRC Logfiles in JSON, JSONP, Text/Plain and HTML

## Request
POST or GET requests
* Parse and return lines from an IRC Logfile
* Enable sorting, searching and filtering of IRC Logfiles
* Return Data ready for json, plain text or html
* @param int item   N or empty for first
* @param int count   N or empty for all
* @param string type   text, plain, json or empty for json
* @param string option   all, talk, min and empty for part, joins and quits only
* @param string sort   desc or asc and empty for asc
* @param string callback   callbackFunction for type=json only
* @param int date   yyyymmdd or empty for current date
* @param string search   search Query or empty for no search

### JSON
```
REQUEST
/api.php?type=json&option=all&date=20161102&count=10&sort=asc&callback=foo

RESPONSE
foo({
  "moreItems":true,
  "search":false,
  "startDate":20160216,
  "endDate":20161118,
  "itemDate":20161102,
  "itemCount":10,
  "itemSort":"asc",
  "itemOption":"all",
  "firstItemId":4080,
  "lastItemId":4089,
  "items":[
    {
      "content":"[23:55:23] *** Quits: cdown (~cdown@replaced\/ip\/or\/hostname) (Read error: Connection reset by peer)",
      "timestamp":"20161102235523",
      "datetime":"2016-11-02T23:55:23",
      "action":"Quits",
      "nickname":"cdown",
      "id":4080
    },
    {
      "content":"[23:55:40] *** Quits: Shentino (~Shentino@replaced\/ip\/or\/hostname) (Ping timeout: 250 seconds)",
      "timestamp":"20161102235540",
      "datetime":"2016-11-02T23:55:40",
      "action":"Quits",
      "nickname":"Shentino",
      "id":4081
    },
    {
      "content":"[23:56:08] *** Joins: Drugo (~Drugo@replaced\/ip\/or\/hostname)",
      "timestamp":"20161102235608",
      "datetime":"2016-11-02T23:56:08",
      "action":"Joins",
      "nickname":"Drugo",
      "id":4082
    },
    {
      "content":"[23:56:33] *** Joins: anuxivm (~anuxi@replaced\/ip\/or\/hostname)",
      "timestamp":"20161102235633",
      "datetime":"2016-11-02T23:56:33",
      "action":"Joins",
      "nickname":"anuxivm",
      "id":4083
    },
    {
      "content":"[23:56:48] *** Quits: netzfisch (~Thunderbi@replaced\/ip\/or\/hostname) (Quit: netzfisch)",
      "timestamp":"20161102235648",
      "datetime":"2016-11-02T23:56:48",
      "action":"Quits",
      "nickname":"netzfisch",
      "id":4084
    },
    {
      "content":"[23:57:32] *** Joins: cdown (~cdown@replaced\/ip\/or\/hostname)",
      "timestamp":"20161102235732",
      "datetime":"2016-11-02T23:57:32",
      "action":"Joins",
      "nickname":"cdown",
      "id":4085
    },
    {
      "content":"[23:57:51] *** Quits: silverhom (~silverhom@replaced\/ip\/or\/hostname) (Quit: Leaving)",
      "timestamp":"20161102235751",
      "datetime":"2016-11-02T23:57:51",
      "action":"Quits",
      "nickname":"silverhom",
      "id":4086
    },
    {
      "content":"[23:58:35] *** Quits: gonz0 (~gonz0@replaced\/ip\/or\/hostname) (Quit: fui)",
      "timestamp":"20161102235835",
      "datetime":"2016-11-02T23:58:35",
      "action":"Quits",
      "nickname":"gonz0",
      "id":4087
    },
    {
      "content":"[23:59:13] *** Quits: Groscheri (~Dyonisos@replaced\/ip\/or\/hostname) (Quit: Quis custodiet ipsos custodes ?)",
      "timestamp":"20161102235913",
      "datetime":"2016-11-02T23:59:13",
      "action":"Quits",
      "nickname":"Groscheri",
      "id":4088
    },
    {
      "content":"[23:59:30] *** Joins: Dadou (~Dadou@replaced\/ip\/or\/hostname)",
      "timestamp":"20161102235930",
      "datetime":"2016-11-02T23:59:30",
      "action":"Joins",
      "nickname":"Dadou",
      "id":4089
    }
  ]
});

```
### HTML
```
REQUEST
/api.php?type=html&count=10

RESPONSE

<!-- START: new items 1479485933 -->
<li id="item2512">
  <time datetime="2016-11-18T17:15:01">
    [17:15:01]  </time>
    <strong>
    techno29001  </strong>
  <em>
    Joins  </em>
  </li>
<li id="item2513">
  <time datetime="2016-11-18T17:15:13">
    [17:15:13]  </time>
    <strong>
    sappel  </strong>
  <em>
    Joins  </em>
  </li>
<li id="item2515">
  <time datetime="2016-11-18T17:15:26">
    [17:15:26]  </time>
    <strong>
    amcorreia  </strong>
  <em>
    Joins  </em>
  </li>
<li id="item2516">
  <time datetime="2016-11-18T17:15:36">
    [17:15:36]  </time>
    <strong>
    ByteStorm  </strong>
  <em>
    Quits  </em>
  </li>
<li id="item2517">
  <time datetime="2016-11-18T17:15:45">
    [17:15:45]  </time>
    <strong>
    netadmin  </strong>
  <em>
    Quits  </em>
  </li>
<li id="item2518">
  <time datetime="2016-11-18T17:17:02">
    [17:17:02]  </time>
    <strong>
    hualet  </strong>
  <em>
    Joins  </em>
  </li>
<li id="item2520">
  <time datetime="2016-11-18T17:17:15">
    [17:17:15]  </time>
    <strong>
    Elirips  </strong>
  <em>
    Quits  </em>
  </li>
<li id="item2521">
  <time datetime="2016-11-18T17:18:15">
    [17:18:15]  </time>
    <strong>
    misterbee  </strong>
  <em>
    Joins  </em>
  </li>
<li id="item2522">
  <time datetime="2016-11-18T17:18:15">
    [17:18:15]  </time>
    <strong>
    eumel  </strong>
  <em>
    Joins  </em>
  </li>
<li id="item2524">
  <time datetime="2016-11-18T17:18:24">
    [17:18:24]  </time>
    <strong>
    Guest49273  </strong>
  <em>
    Quits  </em>
  </li>
<script type="text/javascript">
  var firstItemId = 2512;
  var lastItemId = 2524;
  var itemCount =  10;
  var itemDate = 20161118;
  var itemSort = "asc";
  var option = "min";
  var search = "";
  var moreItems = 1;
</script>
<!-- END: new items 1479485933 -->

```
### PLAIN
```
REQUEST
/api.php?type=plain&item=5

RESPONSE
00:01:14 luckman212 Quits
00:01:35 canopus Quits
00:02:21 kerunaru Quits
00:03:30 alexp Quits
00:05:58 CapsAdmin Joins
00:06:02 akwiatkowski Quits
00:06:24 canopus Joins
00:07:38 nidr0x Quits
00:08:55 user2343234 Joins
00:08:56 luckman212 Joins
00:09:16 Poppabear Quits
00:09:21 toastedmilk Quits
5 17 12 20161118 asc min none 1

```

## Links
* her0 API https://github.com/exiguus/her0.api
* her0 API test https://github.com/exiguus/her0.api.test
