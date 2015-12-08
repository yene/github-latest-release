<?php

require_once("credentials.php");

if (isset($_GET["q"]) && !empty($_GET["q"])) {
  $apiURL = "https://api.github.com/repos/" . $_GET["q"] . "/releases/latest";

  $apiResult = curl_get_file_contents($apiURL);
  $json = json_decode($apiResult, true);

  if (count($json["assets"]) == 1) {
    $downloadURL = $json["assets"][0]["browser_download_url"];

    header("Location: " . $downloadURL);
    die();
  } else {
    http_response_code(404);
    die("Error: no release found");
  }
}

function curl_get_file_contents($URL) {
  $c = curl_init();
  $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
  curl_setopt($c, CURLOPT_USERAGENT, $agent);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($c, CURLOPT_URL, $URL);

  $contents = curl_exec($c);
  curl_close($c);

  if ($contents) return $contents;
      else return FALSE;
}


?>
<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GitHub release redirect</title>
  <link rel="stylesheet" type="text/css" href="tacit.min.css"/>
  </style>
</head>
<body>
  <section>
    <article>
      <h1>Github 302</h1>
      <form method="get" action="">
        <fieldset>
          <label for="q">Enter a github repo and get redirected to the latest binary release:</label>
          <input name="q" type="text" id="q" size="25" placeholder="yene/Spotify-Video" autofocus/>
          <button type="submit">Give me the release!</button>
        </fieldset>
      </form>
      <p><small>You can add <code>?q=yene/Spotify-Video</code> to the URL for an instant redirect.</small></p>
    </article>
    <footer>
        <nav>
          <ul>
            <li>
              <small>Made by <a href="http://yannickweiss.com">Yannick Weiss</a></small>
            </li>
          </ul>
        </nav>
    </footer>
  </section>
</body>
</html>
