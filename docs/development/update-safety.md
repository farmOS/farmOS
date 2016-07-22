# Developing with updates in mind

farmOS is [built on Drupal], which means that it is extremely flexible. If you
are installing your own instance of farmOS, you have full control over the
entities, fields, Views, etc. So you can change things that farmOS sets up by
default. This is great! And it's part of the reason why farmOS can grow and
evolve so quickly. But, with great power comes great responsibility.

The danger of this comes when it's time to [update your farmOS] instance to a
new version. If you've made modifications to core fields or entity types, they
may no longer be compatible with the "main line" of farmOS development.

Think about it like this: when you make a change to the configuration of your
system, you are essentially creating a new "branch" of farmOS. It's no longer
default farmOS, in other words. It's your own unique configuration.

When a new official version of farmOS is released, your modifications may
conflict with those in the new release. Depending on what these changes are, it
can be pretty easy to merge them together... or it can be a nightmare.

So, as a general recommendation: if you are modifying the inner workings of
farmOS, you should either be very familiar with what you're doing and how it
will affect updates, or you shouldn't do it with your live data. Set up a
second testing site, make some modifications, and then open up a new issue in
the [farmOS issue queues] and suggest your changes for inclusion in the project!

[built on Drupal]: /development/drupal
[update your farmOS]: /hosting/updating
[farmOS issue queues]: /development/issue-queues

