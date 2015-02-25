Hop Mailinglist at login
========================

This Expressionengine Extension will subscribe a user to a mailing list when logging in.

Use
---
Simply add a form field called `mailing_list` into your login form, and set its value to the mailing list id.

`<input name="mailing_list" id="mailing_list" value="2" type="checkbox">`

The extension will take care of adding the user to the mailing list.

Note : if the user is not logged in (wrong login or password), it will not be added to the list.