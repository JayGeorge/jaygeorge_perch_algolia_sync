# Installation
- If you are downloading this app as a zip file from GitHub, rather than installing as a submodule, you should rename the downloadded folder from `jaygeorge_perch_algolia_sync-master` to `jaygeorge_perch_algolia_sync`
- Drop the `jaygeorge_perch_algolia_sync` folder into your `/perch/addons/apps` folder
- Log into Perch
- You should now see the app name in the left column of your Perch admin
- Go into the app in the admin and follow instructions

# Installing as a Git Submodule (optional but recommended)
To keep this app up to date easily I recommend you install it as a Git submodule to your project. You can then check for updates easily rather than re-downloading this project.

Here are some brief instructions:

- Make sure you're inside your main project directory
- Run the command `git submodule add https://github.com/JayGeorge/jaygeorge_perch_algolia_sync.git perch/addons/apps/jaygeorge_perch_algolia_sync`

This command will add the Algolia Sync app into the directory `perch/addons/apps/`

Run `git status`. You should notice a new `.gitmodules` file if this is the first time you've added a submodule to your repo. This is a configuration file that stores the mapping between the project’s URL and the local subdirectory you’ve pulled it into.

## Updating the app
- Make sure you're inside your main project directory
- Run the command `git submodule update --remote`. This will update all submodules, including this Perch Algolia Sync repo.

You'll see your main repo will show an update is ready to be staged. It will say something like `Subproject commit e2641da9d0df04df322e983d612d576d43393b67`. You can simply stage this commit with a message like "Submodule Update" or something more meaningful.

## Downloading a repository that contains this submodule
_E.g. If you've previously installed this app as a submodule and you're now re-downloading your parent project to a new machine…_

[Source](https://git-scm.com/book/en/v2/Git-Tools-Submodules)

When you clone such a project, by default you get the directories that contain submodules, but none of the files within them yet.
The easiest way is to always clone recursively with this command:

    git clone something --recurse-submodules

If you've already downloaded a repo and want to fetch submodules you can do this:

`git submodule init` to initialize your local configuration file
`git submodule update` to fetch all the data from that project and check out the appropriate commit listed in your superproject:

# About this App
This Perch App uses the Algolia API to communicate with your Algolia account and update your records. It updates the record name and images by default.

NB **This App only handles the backend i.e. syncing your Perch website with Algolia's database**. You'll need to communicate with Algolia on the front-end and output search results.

For example you may want to load Algolia scripts on the front end like this:

```
    <script defer src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
```

## Why would you use this App?
- While you could dump a JSON file of current records to generate a search powered by Algolia, the JSON file is immediately out of date as soon as another record is updated in Perch.
- By communicating changes in the Perch admin to Agolia via its API we can ensure that search results stay in sync your Perch-powered website.

### Future Improvements
- I aim to make the app a bit more dynamic—for example at the moment I have programmed an alternative API key to be inputted when "-portfolio" is in the URL. This should ideally be set dynamically rather than hard-coded.

### Known Bugs
- Sometimes you need to save the record twice for it to successfully communicate to Algolia. I'm not sure why this is.