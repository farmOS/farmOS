# Documentation

In addition to the code for farmOS, this repository includes the source files of the
documentation which is hosted at [http://farmOS.org](http://farmos.org).

It uses [mkdocs](http://www.mkdocs.org) to convert simple markdown files into
static HTML files.

To get started contributing to the farmOS documentation, fork
[farmOS](https://github.com/farmOS/farmOS) on Github. Then install mkdocs and
clone this repo:

    $ brew install python                # For OSX users
    $ sudo apt-get install python-pip    # For Debian/Ubuntu users
    $ sudo pip install mkdocs mkdocs-material
    $ git clone https://github.com/farmOS/farmOS.git farmOS
    $ cd farmOS
    $ git remote add sandbox git@github.com:<username>/farmOS.git
    $ mkdocs serve

Your local farmOS documentation site should now be available for browsing:
http://127.0.0.1:8000/. When you find a typo, an error, unclear or missing
explanations or instructions, hit ctrl-c, to stop the server, and start editing.
Find the page you’d like to edit; everything is in the docs/ directory. Make
your changes, commit and push them, and start a pull request:

    $ git checkout -b fix_typo              # Create a new branch for your changes.
    ...                                     # Make your changes.
    $ mkdocs build --clean; mkdocs serve    # Go check your changes.
    $ git diff                              # Make sure there aren’t any unintended changes.
    ...
    $ git commit -am "Fixed typo."          # Useful commit message are a good habit.
    $ git push sandbox fix_typo             # Push your new branch up to your Github sandbox.

Visit your fork on Github and start a Pull Request.

For more information on writing and managing documentation with mkdocs, read the
official mkdocs documentation: [http://www.mkdocs.org](http://www.mkdocs.org)
