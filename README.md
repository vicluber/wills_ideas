# wills_ideas
This is an extension for typo3 9.4 and older in which the frontend user can create small posts as typo3 records with short text, a category, an image and a like button. Also with a function to inform to an email that a new idea has been posted. The ideas get created disabled in order to be aproved before posted.

Missing:
Fall back if the editor forgot to set a private side for loged users at the frontend. If the extension is set for all users once you try to create an idea you will get an error message of not finding the logged user id.

1- Add the extension
2- If necessary load the classes on typo3's composer.json file
3- Once ins installed you will need to create some of the typo3 system categories to set in the plugin
4- You are gonna need to set the frontend user on your typo3 in order to set the permissions to the page where you add the plugins.
5- Then is just matter to add the plugins and configure them, you will be asked for:
  * A parent category that holds all the categories for Ideas
  * To select the page where the New Ideas plugin has been added.
  * To select the page where the List Ideas plugin has been added.
  * The email to send the notification to.
  * And a storage folder to save all the ideas.
