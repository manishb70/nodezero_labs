How to Hack It (Walkthrough)
Reconnaissance:

Go to ?page=login. Try logging in as admin (fails).

Log in as guest / guest to see the dashboard. It says "Standard User".

Log out.

Request Reset:

Go to Forgot Password.

Enter username: guest.

Click Send Reset Link.

A green box appears with a "simulated email link".

?page=reset&token=...&user=guest.

Click that link.

The Attack (Burp Suite or Inspect Element):

You are now on the "Set New Password" page for guest.

Inspect Element on the form fields.

Find the hidden input: <input type="hidden" name="username" value="guest">.

Change it: Double click guest and change it to admin.

Enter a new password (e.g., hacked123) in the visible box.

Click Change Password.

Login as Admin:

The site says "Password successfully changed for user: admin".

Go to Login.

Enter admin / hacked123.

Get Flag:

You are now on the Dashboard as Admin.

You see the red "Danger Zone".

Click DELETE DATABASE.

The flag is revealed.