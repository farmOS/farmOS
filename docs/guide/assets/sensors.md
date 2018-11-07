# Sensors

In addition to manually-entered records, farmOS also provides a framework for
receiving data from automated environmental sensors. The *Farm Sensor* module
adds a **Sensor** [asset](/guide/assets) type, which can be tracked like any
other asset.

Sub-modules (like *Farm Sensor: Listener*) extend the Sensor asset type by
providing integration with external devices. Additional modules can be built to
connect to specific types of sensors, if necessary.

It is possible to assemble your own sensors with inexpensive components and
send their data to farmOS without any soldering or programming.

<video width="100%" controls>
  <source src="/demo/sensors.mp4" type="video/mp4">
</video>

## Farm Sensor: Listener

The *Farm Sensor: Listener* module is a general-purpose sensor sub-module that
provides a simple "Listener" sensor type. Each sensor asset that is denoted as
a listener receives a unique URL with a public and private key that data can be
pushed to using standard [HTTP] requests with [JSON]-encoded data. Data is
stored in the database and is displayed in the sensor asset within farmOS.

Specific instructions are provided in the farmOS interface itself when you
create a listener sensor asset. Refer to those instructions for more
information, as well as sample code and JSON formatting.

The listener module is useful for simple data streams. For more complex data, a
more customized sub-module may be necessary.

### GrovePi + Node Red

If you are looking for a DIY approach to collecting sensor data that doesn't
require soldering or coding check out this guide to
[Collecting sensor data in farmOS using GrovePi and Node-RED](http://farmhack.org/tools/collecting-sensor-data-farmos-using-grovepi-and-node-red)
on [Farm Hack](http://farmhack.org).

<iframe width="100%" height="480" src="https://www.youtube.com/embed/rCl06YBb4cM" frameborder="0" allowfullscreen></iframe>

### Open Pipe Kit

The [Open Pipe Kit] project provides a command-line script that can be used to
push data to farmOS from various sensors using the *Farm Sensor: Listener*
module. The following video demonstrates how to set it up. For more information
about Open Pipe Kit, refer to: [http://openpipekit.github.io](http://openpipekit.github.io)

Open Pipe Kit farmOS CLI: [https://github.com/openpipekit/farmos-cli](https://github.com/openpipekit/farmos-cli)

<iframe width="100%" height="480" src="https://www.youtube.com/embed/fCflGeOpTqk" frameborder="0" allowfullscreen></iframe>

### Notifications

The *Farm Sensor: Listener* module comes with a basic alert notification
mechanism that can be configured to send an email or text message if an
incoming value is above or below a given threshold.

**Text messages:** It is possible to send text messages by entering a special
email address that corresponds to your phone number and mobile carrier.

Here are the domain names used for some of the more popular phone carriers:

* US Cellular: `[number]@email.uscc.net`
* Verizon: `[number]@vtext.com`
* Virgin: `[number]@vmobl.com`
* AT&T: `[number]@txt.att.net`
* Nextel: `[number]@messaging.nextel.com`
* Sprint: `[number]@messaging.sprintpcs.com`
* T-Mobile: `[number]@tmomail.net`
* republic wirelsss: `[number]@text.republicwireless.com`

Use the phone number of the person you are texting followed by the domain name
corresponding to their carrier. For example, if you are sending a text message
to a Nextel subscriber with the phone number `232-232-2323`, you would enter
`2322322323@messaging.nextel.com`.

[HTTP]: https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol
[JSON]: https://en.wikipedia.org/wiki/JSON
[sensor development]: /development/sensors
[Open Pipe Kit]: http://openpipekit.github.io

