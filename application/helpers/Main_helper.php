<?php
function getFromAPI($url, $post)
{
  $options = array(
    "http" => array(
      "method" => "POST",
      "header" => "Content-Type: application/x-www-form-urlencoded",
      "content" => http_build_query($post)
    )
  );
  $data = file_get_contents($url, false, stream_context_create($options));
  return json_decode($data, true);
}

function simpelkan()
{
  return 'http://localhost/simpelkan/api/index.php?page=';
}
