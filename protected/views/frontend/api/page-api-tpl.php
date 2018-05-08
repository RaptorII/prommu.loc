<div class="col-md-10">
<link rel="stylesheet" href="https://prommu.com/css/api.css" media="screen">
<link rel="stylesheet" href="https://prommu.com/css/vendor.css" media="screen">
<h1 id="api-toc">Список запросов</h1>
<ol>
<li><a style="font-size: 20px;" href="https://prommu.com/api#api-intro">Авторизация</a>
<!-- <ul>
<li><a href="https://prommu.com/api#common-pitfalls">Частые вопросы</a></li>
</ul> -->
</li>
<li><a style="font-size: 20px;" href="https://prommu.com/api#api-mess">Сообщения</a>
<li><a style="font-size: 20px;" href="https://prommu.com/api#api-vac">Вакансии</a>
<li><a style="font-size: 20px;" href="https://prommu.com/api#api-prom">Соискатели</a>
<li><a style="font-size: 20px;" href="https://prommu.com/api#api-invite">Отклики/Заявки</a>

</ol>
<br>
<h1 id="api-intro">Общее описание</h1>
При автозирации способом идентификации пользователя служит токен, который формируется индивидуально для каждого пользователя. Используется сертификат ssl, все данные, которые передаються и возвращаются методами PROMMU API защищены и безопасны благодаря протоколу https.
Апи использует автоматический фильтр для определения контекста предложения, поэтому если в методах передачи сообщения в чат посредством переменной mess будет передано сообщение, с текстом, нарущающим правила сервиса - ваш аккаунт АПИ и на сервисе Промму будет заблокирован. 
<br>
<h1 id="api-intro">Авторизация</h1>

Пример на php:
<pre class="with-code"><code class="php hljs">$api_token = <span class="hljs-string">'prommu'</span>; <span class="hljs-comment">// ваш идентификатор</span>
$api_secret = <span class="hljs-string">'676c50c6923194b6a2c0119a6e61c18e5a8cc901'</span>; <span class="hljs-comment">// ваш секретный ключ</span>

$url = <span class="hljs-string">"https://prommu.com/api.auth_user"</span>;
$method = <span class="hljs-string">"POST"</span>;
$signature = sign($api_secret, $url, $method); <span class="hljs-comment"></span>

$curl = curl_init();
curl_setopt_array($curl, [
    <span class="hljs-comment">//CURLOPT_HEADER       =&gt; 1,</span>
    CURLOPT_RETURNTRANSFER =&gt; <span class="hljs-number">1</span>,
    CURLOPT_USERPWD        =&gt; $api_token . <span class="hljs-string">":"</span> . $signature,
    CURLOPT_URL            =&gt; $url
]);

$return = curl_exec($curl);
print_r($return);
curl_close($curl);
</code></pre>
<br>
Пример ответа сервера с заголовками:
<pre class="with-code"><code class="http hljs">HTTP/1.1 <span class="hljs-number">200</span> OK
<span class="hljs-attribute">Date</span>: Sat, 09 Nov 2017 10:04:54 GMT
<span class="hljs-attribute">Server</span>: nginx
<span class="hljs-attribute">Vary</span>: Accept
<span class="hljs-attribute">Cache-Control</span>: no-cache, must-revalidate
<span class="hljs-attribute">Expires</span>: 0
<span class="hljs-attribute">Content-Language</span>: ru
<span class="hljs-attribute">Transfer-Encoding</span>: chunked
<span class="hljs-attribute">Content-Type</span>: application/json; charset=utf-8

<span class="perl">[
    {
        <span class="hljs-string">"code"</span>: <span class="hljs-string">"20"</span>,
        <span class="hljs-string">"message"</span>: <span class="hljs-string">"success"</span>,
    }
]
</span></span></code></pre>

<br>
<h1 id="api-mess">Сообщения</h1>

Пример на php:
<pre class="with-code"><code class="php hljs">$api_token = <span class="hljs-string">'prommu'</span>; <span class="hljs-comment">// ваш идентификатор</span>
$api_secret = <span class="hljs-string">'676c50c6923194b6a2c0119a6e61c18e5a8cc901'</span>; <span class="hljs-comment">// ваш секретный ключ</span>

$url = <span class="hljs-string">"https://prommu.com/api.send_mess"</span>;
$method = <span class="hljs-string">"POST"</span>;
$signature = sign($api_secret, $url, $method); <span class="hljs-comment"></span>

$curl = curl_init();
curl_setopt_array($curl, [
    <span class="hljs-comment">//CURLOPT_HEADER       =&gt; 1,</span>
    CURLOPT_RETURNTRANSFER =&gt; <span class="hljs-number">1</span>,
    CURLOPT_USERPWD        =&gt; $api_token . <span class="hljs-string">":"</span> . $signature,
    CURLOPT_URL            =&gt; $url,
    CULPORT_HEADER = array(
    "text:hello",
    "chat:1",
    "new:1",
    )
]);

$return = curl_exec($curl);
print_r($return);
curl_close($curl);
</code></pre>
<br>
Пример ответа сервера с заголовками:
<pre class="with-code"><code class="http hljs">HTTP/1.1 <span class="hljs-number">200</span> OK
<span class="hljs-attribute">Date</span>: Sat, 09 Nov 2017 10:04:54 GMT
<span class="hljs-attribute">Server</span>: nginx
<span class="hljs-attribute">Vary</span>: Accept
<span class="hljs-attribute">Cache-Control</span>: no-cache, must-revalidate
<span class="hljs-attribute">Expires</span>: 0
<span class="hljs-attribute">Content-Language</span>: ru
<span class="hljs-attribute">Transfer-Encoding</span>: chunked
<span class="hljs-attribute">Content-Type</span>: application/json; charset=utf-8

<span class="perl">[
    {
        <span class="hljs-string">"code"</span>: <span class="hljs-string">"20"</span>,
        <span class="hljs-string">"message"</span>: <span class="hljs-string">"success"</span>,
    }
]
</span></span></code></pre>
<br/>
<h1 id="api-vac">Вакансии</h1>

Пример на php:
<pre class="with-code"><code class="php hljs">$api_token = <span class="hljs-string">'prommu'</span>; <span class="hljs-comment">// ваш идентификатор</span>
$api_secret = <span class="hljs-string">'676c50c6923194b6a2c0119a6e61c18e5a8cc901'</span>; <span class="hljs-comment">// ваш секретный ключ</span>

$url = <span class="hljs-string">"https://prommu.com/api.vacancy_own"</span>;
$method = <span class="hljs-string">"GET"</span>;
$signature = sign($api_secret, $url, $method); <span class="hljs-comment"></span>

$curl = curl_init();
curl_setopt_array($curl, [
    <span class="hljs-comment">//CURLOPT_HEADER       =&gt; 1,</span>
    CURLOPT_RETURNTRANSFER =&gt; <span class="hljs-number">1</span>,
    CURLOPT_USERPWD        =&gt; $api_token . <span class="hljs-string">":"</span> . $signature,
    CURLOPT_URL            =&gt; $url
]);

$return = curl_exec($curl);
print_r($return);
curl_close($curl);
</code></pre>
<br>
Пример ответа сервера с заголовками:
<pre class="with-code"><code class="http hljs">HTTP/1.1 <span class="hljs-number">200</span> OK
<span class="hljs-attribute">Date</span>: Sat, 09 Nov 2017 10:04:54 GMT
<span class="hljs-attribute">Server</span>: nginx
<span class="hljs-attribute">Vary</span>: Accept
<span class="hljs-attribute">Cache-Control</span>: no-cache, must-revalidate
<span class="hljs-attribute">Expires</span>: 0
<span class="hljs-attribute">Content-Language</span>: ru
<span class="hljs-attribute">Transfer-Encoding</span>: chunked
<span class="hljs-attribute">Content-Type</span>: application/json; charset=utf-8

<span class="perl">[
    {
        <span class="hljs-string">"vacancy"</span>: <span class="hljs-string">"20"</span>,
        <span class="hljs-string">"details"</span>: <span class="hljs-string">"{id:20, title:New Vac, remdate:29.02.2018, crdate: 20.11.2018, conditions:Обязанности, условия, требования}"</span>,
    }
]
</span></span></code></pre>
<br/>
<h1 id="api-prom">Соискатели</h1>

Пример на php:
<pre class="with-code"><code class="php hljs">$api_token = <span class="hljs-string">'prommu'</span>; <span class="hljs-comment">// ваш идентификатор</span>
$api_secret = <span class="hljs-string">'676c50c6923194b6a2c0119a6e61c18e5a8cc901'</span>; <span class="hljs-comment">// ваш секретный ключ</span>

$url = <span class="hljs-string">"https://prommu.com/api.promo_search"</span>;
$method = <span class="hljs-string">"GET"</span>;
$signature = sign($api_secret, $url, $method); <span class="hljs-comment"></span>

$curl = curl_init();
curl_setopt_array($curl, [
    <span class="hljs-comment">//CURLOPT_HEADER       =&gt; 1,</span>
    CURLOPT_RETURNTRANSFER =&gt; <span class="hljs-number">1</span>,
    CURLOPT_USERPWD        =&gt; $api_token . <span class="hljs-string">":"</span> . $signature,
    CURLOPT_URL            =&gt; $url
]);

$return = curl_exec($curl);
print_r($return);
curl_close($curl);
</code></pre>
<br>
Пример ответа сервера с заголовками:
<pre class="with-code"><code class="http hljs">HTTP/1.1 <span class="hljs-number">200</span> OK
<span class="hljs-attribute">Date</span>: Sat, 09 Nov 2017 10:04:54 GMT
<span class="hljs-attribute">Server</span>: nginx
<span class="hljs-attribute">Vary</span>: Accept
<span class="hljs-attribute">Cache-Control</span>: no-cache, must-revalidate
<span class="hljs-attribute">Expires</span>: 0
<span class="hljs-attribute">Content-Language</span>: ru
<span class="hljs-attribute">Transfer-Encoding</span>: chunked
<span class="hljs-attribute">Content-Type</span>: application/json; charset=utf-8

<span class="perl">[
    {
        <span class="hljs-string">"promo_search"</span>: <span class="hljs-string">"{promo:1, name:Prommu, firstname: Prommu, project: 3, age: 23, vacancy: 111, post:122}"</span>,
        <span class="hljs-string">"message"</span>: <span class="hljs-string">"success"</span>,
    }
]
</span></span></code></pre>
<br/>
<h1 id="api-invite">Отклики/заявки</h1>

Пример на php:
<pre class="with-code"><code class="php hljs">$api_token = <span class="hljs-string">'prommu'</span>; <span class="hljs-comment">// ваш идентификатор</span>
$api_secret = <span class="hljs-string">'676c50c6923194b6a2c0119a6e61c18e5a8cc901'</span>; <span class="hljs-comment">// ваш секретный ключ</span>

$url = <span class="hljs-string">"https://prommu.com/api.invite"</span>;
$method = <span class="hljs-string">"GET"</span>;
$signature = sign($api_secret, $url, $method); <span class="hljs-comment"></span>

$curl = curl_init();
curl_setopt_array($curl, [
    <span class="hljs-comment">//CURLOPT_HEADER       =&gt; 1,</span>
    CURLOPT_RETURNTRANSFER =&gt; <span class="hljs-number">1</span>,
    CURLOPT_USERPWD        =&gt; $api_token . <span class="hljs-string">":"</span> . $signature,
    CURLOPT_URL            =&gt; $url
]);

$return = curl_exec($curl);
print_r($return);
curl_close($curl);
</code></pre>
<br>
Пример ответа сервера с заголовками:
<pre class="with-code"><code class="http hljs">HTTP/1.1 <span class="hljs-number">200</span> OK
<span class="hljs-attribute">Date</span>: Sat, 09 Nov 2017 10:04:54 GMT
<span class="hljs-attribute">Server</span>: nginx
<span class="hljs-attribute">Vary</span>: Accept
<span class="hljs-attribute">Cache-Control</span>: no-cache, must-revalidate
<span class="hljs-attribute">Expires</span>: 0
<span class="hljs-attribute">Content-Language</span>: ru
<span class="hljs-attribute">Transfer-Encoding</span>: chunked
<span class="hljs-attribute">Content-Type</span>: application/json; charset=utf-8

<span class="perl">[
    {
        <span class="hljs-string">"vacancy"</span>: <span class="hljs-string">"20"</span>,
        <span class="hljs-string">"invite"</span>: <span class="hljs-string">"{promo:21, 24,192,77}"</span>,
    }
]
</span></span></code></pre>

</div>