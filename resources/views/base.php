<!--
Copyright (C) 2013-2015 Chaoyi Zha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

<!DOCTYPE html>
<html>
<head>
    <title><!-- TODO output name --></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- TODO output theme stylesheet -->
    <link rel="stylesheet" href="" />
    <link rel="stylesheet" href="css/base.css" />
    <!-- TODO remove from CDN -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" />
    <!-- TODO remove from CDN -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
    {% block css %}{% endblock %}
</head>
<body>
    {% include "navbar" %}
    <div class="container">
        <!-- TODO jumbotron or other here -->
        <div class="contentDiv {% if large %}jumbotron{% endif %}">
            {% block content %}
        </div>
    </div>
    <!-- TODO remove from CDN -->
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    {% block js %}{% endblock %}
</body>
</html>
