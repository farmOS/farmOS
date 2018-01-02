# Eggs

[https://github.com/farmOS/farm_eggs]

The Eggs module for farmOS provides a shortcut for adding egg collection logs.
If you have chickens, ducks, or other egg-laying birds, this makes it very fast
and easy to record egg harvests from your phone on your way back from the coop.

The shortcut form is available via an "Eggs" tab on the farmOS dashboard (the
path will be `/farm/eggs`). It allows you to specify a quantity and,
optionally, a [group] or [animal] asset to associate it with.

When you first install the module, the "Group/animal" reference field won't be
available. You need to enable it on specific [group] or [animal] assets in
order to see them as checkbox options. To do so, navigate to the asset you
would like, click the "Edit" tab, and find the checkbox labeled "This
group/animal produces eggs". Check the box, save the asset, return to the
Eggs form, and you should see the asset available as an option.

When you submit the egg form, a harvest log will be created with the log name,
quantity, and asset reference fields filled in automatically.

[https://github.com/farmOS/farm_eggs]: https://github.com/farmOS/farm_eggs
[group]: /guide/assets/groups
[animal]: /guide/assets/animals

