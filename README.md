# Polr
#####v2 Devel

:aerial_tramway: A modern, minimalist, and lightweight URL shortener.



[![GitHub issues](https://img.shields.io/github/issues/cydrobolt/polr.svg)]()
[![GitHub license](https://img.shields.io/github/license/cydrobolt/polr.svg)]()
[![Github Releases](https://img.shields.io/github/downloads/cydrobolt/polr/latest/total.svg)]()
[![GitHub release](https://img.shields.io/github/release/cydrobolt/polr.svg)]()



Welcome to Polr's 2.0 development branch. 
Please keep in mind 2.0 is pre-alpha, and should not be used in production. This version of Polr break existing 0.x and 1.x installations, but migrations will be provided. 

Once 2.0 is complete, it will supercede the current stable version of Polr, but the latest 1.x version will continue to be available as a legacy release for users who cannot make the switch to 2.0 due to their host or do not meet the new server requirements.

Polr 2.0 moves away from `mysqli`, rather taking on `PDO` with `Eloquent`. Routing and autoloading will be done with `composer` and the Lumen web framework. Although unlikely, this change may cause those on shared hosting to be unable to install Polr.

####License


    Copyright (C) 2015 Chaoyi Zha

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
