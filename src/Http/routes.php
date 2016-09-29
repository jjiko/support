<?php
Route::get('sha256', function () {
  return hash('sha256', Input::server('REMOTE_ADDR'));
});

Route::get('status/code/{code}', function ($code) {
  try {
    (new \Illuminate\Http\Response())->setStatusCode($code);
  } catch (InvalidArgumentException $exception) {
    return App::abort(500, $exception->getMessage());
  }

  return App::abort($code);
});

Route::get('mail/test', function () {
  try {
    $params = [
      'email' => 'somebody@gmail.com',
      'body' => 'This is yet another test'
    ];
    Mail::send('emails.contact', $params, function ($message) {
      $message
        ->from('contact@joejiko.com', 'JoeJiko.com')
        ->to('joejiko@gmail.com', 'Joe Jiko')
        ->subject('Contact @joejiko.com');
    });
  } catch (Exception $e) {
    return $e->getMessage();
  }
  return "okay..";
});

Route::get('sb/push', function () {
  return view('sb.push');
});

Route::post('sb/push/message', function () {
  $client = new \Endroid\Gcm\Client('AIzaSyCcQHgBEYNK4R6QgEEWsr5wu-zHKGkXUeI');
  $registrationIds = [
    'APA91bHJ4kOvrnAS-MhRWscLkgcevD0hyx9pfSM00IfxN3D5PSRaD79f6ZHfw65ZLp_mMCrkgKumnpKIMLsdxD7lWqtqhjQpWmAie-xei-kGKSzqOv4z2t1-XuqOPGLqiH4oxnjR2AM8EjVCu5FVOUUKwC23cMPNFnEl4gEZj8EeAilg4L4gX2A'
  ];
  $data = array(
    'title' => Input::get('title'),
    'message' => Input::get('message')
  );
  $success = $client->send($data, $registrationIds);

  dd($client->getResponses());
});