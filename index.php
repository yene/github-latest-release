<?php

$allowedExtensions = ["zip", "dmg", "tar.gz", "pkg", "xip", "exe", "app"];

require_once("credentials.php");

if (isset($_GET["q"]) && !empty($_GET["q"])) {
  $query = $_GET["q"];
  $query = str_replace("https://github.com/", "", $query);
  $query = trim($query, '/');
  $apiURL = "https://api.github.com/repos/" . $query . "/releases/latest";
  $apiURL .= "?client_id=" . $client_id . "&client_secret=" . $client_secret;

  $apiResult = curl_get_file_contents($apiURL);
  $json = json_decode($apiResult, true);

  if (count($json["assets"]) == 1) {
    $downloadURL = $json["assets"][0]["browser_download_url"];
    header("Location: " . $downloadURL);
    die();
  } else if (count($json["assets"]) > 1) {
    foreach ($json["assets"] as $key => $value) {
      $ext = pathinfo($value["browser_download_url"], PATHINFO_EXTENSION);
      if (in_array($ext, $allowedExtensions)) {
        header("Location: " . $value["browser_download_url"]);
        die();
      }
    }
    http_response_code(404);
    die("Error: No binary release found.");
  } else {
    http_response_code(404);
    die("Error: No binary release found.");
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
  <title>GitHub Latest Release</title>
  <link rel="stylesheet" type="text/css" href="tacit.min.css"/>
  </style>
</head>
<body>
  <section>
    <article>
      <h1>GitHub Latest Release</h1>
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
