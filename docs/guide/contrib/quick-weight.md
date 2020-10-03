# Quick Weight Form

[https://github.com/Skipper-is/farm_quickweight]

The Quick Weight Form module for FarmOS provides an additional quick form for
logging livestock weights from the Dashboard. This makes the process of logging
weights very quick for single animals, and if you have to go through the whole
herd.

The shortcut for the form is available viathe "Weight" tab on the FarmOS
dashboard, under Quick Forms. (You can also find the form under
'/farm/quick/quickweight')

It allows you to specify the weight, unit of measurement and the [group] or
[animal] asset to associate it with.

Once installed, you will need to enable it under Quick Forms -> Configure
(/farm/quick/configure)

When you submit the quick weight form, it will create an observation log with
the weight information attatched to the record. It will reset the Group/animal
and quantity fields ready for the next animal to be recorded.

[https://github.com/Skipper-is/farm_quickweight]: https://github.com/Skipper-is/farm_quickweight
[group]: /guide/assets/groups
[animal]: /guide/assets/animals

