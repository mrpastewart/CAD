# CAD
Computer aided dispatch system


### Compatibility requirements
- Must be able to work on mobiles for normal MDT & Civ MDT.
- Must be able to work on desktop screens for MDT, Civ MDT & Dispatch.
- Internet explorer 11 minimum requirement.


### Feature list
- Right click feature for units (as is now)
- ANPR system (as is now)
- Show cads in list (list side by side)
- Click (edit) to bring up the cad on edit
- CAD review system for civilian calls
-  Ability to manually create CADs
- View of available/committed/unavailable units
- Show units in order (fp, cs, rtpc, trojan)
- Notification of new CAD (sounds etc)
- Send people to cad with a role (las, lfb etc)
- Attach units to CAD by text and drag
- Ability to add persons and vehicles to PNC with basic info when creating CAD
- Control can create event channels
- As well as operational and final updates on MDT, ability to add fixed penalty notices etc to persons and vehicles (will clarify)
- Add FCR messages to all, civs and officers
- See whos viewing the dispatcher
- Live Map
- See units attached on MDT
- See incident log on MDT of when units attached and when they state 6 etc.
- Notification or visual queue that an officer has put an update on CAD
- Control can point to point two units which will create them a locked temp channel and move them there
- Potential switch livemap to use street names

### Tech stack
- Slim PHP
- Mysqli
- Vue.js


```
-- Create syntax for TABLE 'cad_incident_logs'
CREATE TABLE `cad_incident_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `incident_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `added` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'cad_incident_unit'
CREATE TABLE `cad_incident_unit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `incident_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `added` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `notes` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'cad_incidents'
CREATE TABLE `cad_incidents` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) DEFAULT NULL,
  `location1` varchar(512) DEFAULT NULL,
  `location2` varchar(512) DEFAULT NULL,
  `type` varchar(256) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `grading` enum('D','S','I') DEFAULT NULL,
  `status` int(11) unsigned DEFAULT 1,
  `owner_id` int(11) unsigned DEFAULT NULL,
  `interop` int(11) unsigned DEFAULT NULL,
  `added` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'cad_unit_user'
CREATE TABLE `cad_unit_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `added` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `unit_id` (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'cad_units'
CREATE TABLE `cad_units` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `added` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
```
