# Frequently Asked Questions

## What is farmOS?

> What is the farmOS software used for?

farmOS is a web-based application for agricultural management, planning, and
record keeping.

## Who is using farmOS?

> Are there a lot of farms using farmOS?

A brief list of farms that are using farmOS is available here:
[Farms using farmOS]. Anyone using farmOS can add their farm's name to this
list.

You can also refer to the [Drupal.org Usage Statistics] for farmOS, which shows
how many active farmOS installations are out there in the wild. Note that this
only includes installations that have their "Update" module enabled.

## How do I use farmOS?

> How do I start using farmOS?

Refer to the [User Guide] to get started with farmOS.

> Does farmOS cost money?

The farmOS software itself is free. Hosting the software on a web server so that
you can access it from anywhere requires paying for web hosting.

Low-cost farmOS hosting is available through [Farmier].

> Can I use farmOS on my laptop/desktop/phone/tablet?

Yes! farmOS is a web application, which means it runs on any device that has a
web browser.

It uses the [Bootstrap framework] to ensure that it looks good on screen sizes
big and small.

> Do I need internet access to use farmOS?

In general: yes. farmOS uses Google Maps for its map base layers, which require
an internet connection to access. And in most cases you will want to host your
farmOS installation on a web server so that it is accessible to everyone who
needs to use it.

With a little ingenuity, however, it should be possible to run farmOS locally,
with your own web server and locally hosted map tiles. You could be the first
to try it! If you do, please share your experience so the rest of the farmOS
community can learn along with you!

> Who owns the data that I enter into farmOS?

You do. farmOS is not owned by any single group or individual, so neither is
your data. You also have full access to the code that is storing and using that
data! Why? Because farmOS is free open source software developed by a community
of farmOS users.

> I see a security update warning. What should I do?

Please follow the instructions in [Updating farmOS](/hosting/updating/)


> Where do I get support if I have questions?

The [farmOS Community Forum] is a great place to ask questions and learn from
others who are using farmOS.

## How can I contribute to the project?

> Can I donate to the project.

Yes, you can donate to the farmOS project through OpenCollective:
[Donate to farmOS]

> Can I contribute to the project in other ways?

Yes, there are [many ways to contribute] to the farmOS project.

## Where can I find news?

> Where are farmOS development updates posted?

Each version of farmOS is released with a set of [release notes] that describe
all of the changes included in the release, with links to detailed descriptions
and community discussions.

When farmOS is mentioned in the media, a link is added to the [Press] page.

Updates on farmOS development are also available on the Farmier newsleter and
[Twitter] account. [Farmier] is run by the lead developer of farmOS.

## Why farmOS?

> Why was the farmOS project started in the first place?

[Eric S. Raymond]'s first main point in *[The Cathedral and the Bazaar]* answers
this question well:

> 1. Every good work of software starts by scratching a developer's personal
> itch.

farmOS started as a hobby project for farm mapping, planning, and record
keeping. It served to fill a gap in the existing software, and provide a
generalized platform that other developers could build upon.

It is now available for free as [open source software].

## Why open source?

> Why was farmOS released under an open source license?

Developing software in an open and collaborative fashion has many benefits.

It encourages curiousity and innovation by allowing anyone to build their own
ideas on top of it. These improvements can be shared with the rest of the
community and merged into the core project itself. It allows for an ecosystem
of organizations, companies, and individuals to work together towards a shared
goal. Thus the community of people involved in developing and using the tools
is not constrained to the staff and customers of a single company.

Large companies and organizations around the world use and develop open source
software every day. [Google's internal open source documentation] summarizes
the benefits of combining efforts: "cooperating is not a zero sum game and that
by working together all participants may yield higher returns than the
investment they make."

> What license is farmOS released under?

As an extension of the [Drupal] project, farmOS is licensed under the
[GNU General Public License (GPL) v2+].

> Who owns the copyright to farmOS?

Similar to Drupal, all farmOS contributors retain copyright on their code, but
agree to release it under the same license as Drupal and farmOS. If you are
unable or unwilling to contribute a patch under the GPL version 2 or later, do
not submit a patch.

> Who owns the farmOS trademark?

farmOS is a registered trademark of [Michael Stenta]. For more information on
the farmOS tradmark and acceptable usage, refer to the
[farmOS Trademark Guidelines].

## Why Drupal?

> Why was farmOS built on Drupal?

Drupal is one of the most widely used open source web application frameworks,
powering some of the largest websites on the internet. It has a huge community
of users and developers who continue to push it forward, completely independent
of farmOS.

Drupal's core functionality can be extended with add-on modules. This means that
new farmOS modules can be written to meet very specific requirements, and users
can choose which modules they need and which they don't. For example, if you
grow crops but you do not raise livestock, you can enable the Crop module, but
leave the Livestock module turned off. Or if you are using a specific type of
sensor to collect environmental data, you can turn on a module that is
specifically made for that sensor. New modules can be written by any developer
who understands Drupal.

It is also possible to build a lot of things directly in the Drupal user
interface, without writing any code. The [Views] and [Rules] modules are two
great examples of this. A lot of the modules that come with farmOS are built as
[Features], which is a module that automatically builds new modules! And we
can't forget the [Openlayers] module, which is used to build all the maps.

Aside from flexibility, security is also a big priority in the Drupal
community. Drupal sites can have multiple user logins, each with an assigned
role and permissions. This allows very fine-grained access control. farmOS uses
this to provide its own set of [roles and permissions].

Last but not least: [internationalization and localization]. Drupal is used
worldwide, and it provides infrastructure to translate farmOS into any language.
Anyone can contribute translations, and they can be shared with the rest of the
farmOS community. If you are interested in contributing a translation in your
language, open an issue in the [issue queues] and let's get started!

[release notes]: https://drupal.org/project/farm/releases
[Press]: /community/press
[Twitter]: https://twitter.com/getfarmier
[Farms using farmOS]: /community/farms
[Drupal.org Usage Statistics]: https://drupal.org/project/usage/farm
[Farmier]: https://farmier.com
[User Guide]: /guide
[Bootstrap framework]: http://getbootstrap.com
[farmOS Community Forum]: https://farmOS.discourse.group
[Donate to farmOS]: /donate
[many ways to contribute]: /community/contribute
[Eric S. Raymond]: https://en.wikipedia.org/wiki/Eric_S._Raymond
[The Cathedral and the Bazaar]: http://www.catb.org/~esr/writings/cathedral-bazaar/cathedral-bazaar
[open source software]: https://en.wikipedia.org/wiki/Open-source_software
[Google's internal open source documentation]: https://opensource.google.com/docs/why
[Drupal]: https://drupal.org
[GNU General Public License (GPL) v2+]: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
[Michael Stenta]: http://mstenta.net
[farmOS Trademark Guidelines]: /community/trademark
[Views]: https://drupal.org/project/views
[Rules]: https://drupal.org/project/rules
[Features]: https://drupal.org/project/features
[Openlayers]: https://drupal.org/project/openlayers
[roles and permissions]: /guide/people
[internationalization and localization]: https://en.wikipedia.org/wiki/Internationalization_and_localization
[issue queues]: /development/issue-queues

