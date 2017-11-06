<h1>Hi, {{ $firstname }}!</h1>
Your new password is: {{ $password }}</p>
<p>Your can change your password when click here!</p>
<a href="{{ $url }}/reset-password?token={{$token}}">Resset password</a>
<p>Thank you!</p>


