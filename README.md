# EconomyPlus  
[![Build Status](https://travis-ci.org/ImagicalGamer/EconomyPlus.svg?branch=master)](https://travis-ci.org/ImagicalGamer/EconomyPlus) 
[![GitLab CI](http://gitlab.com/pogogo007/EconomyPlus/badges/master/build.svg)](https://gitlab.com/pogogo007/EconomyPlus/pipelines?scope=branches)

EconomyPlus is an light Economy plugin built for PocketMine-MP (and all PHP7 alts) with many features!

#Commands

| Command | Argument | Description |
| :-: | :---------: | :---------------: | :---------: |
| /addmoney | `<player> <amount>` | Add Money to a Player! |
| /bal | NA | View your money balance!
| /pay | `<player> <amount>` | Pay a Player!
| /takemoney | `<player> <amount>` | Take Money from a Player! |
| /topmoney | NA | See the TopMoney Stats! |

#Shops

##Sell Shop

| Line1 | Line2 | Line3 | Line4 |
| :---: | :---: | :---: | :---: |
| [Sell] | `<item name>` | `<amount>` | `<price>` |

##Buy Shops

| Line1 | Line2 | Line3 | Line4 |
| :---: | :---: | :---: | :---: |
| [Shop] | `<item name>` | `<amount>` | `<price>` |

##Permission Shops

| Line1 | Line2 | Line3 | Line4 |
| :---: | :---: | :---: | :---: |
| [Perm] | `<price>` | `<perm>` | `<perm cont.>` |

#EconomyPlus Configuration

```yaml
---
# EconomyPlus Config File

# Money a player starts out with
Default-Money: 1000

# Money someone earns when they kill another player
Death-Money: 200

# Default Language EconomyPlus Uses!
# Avalible Languages: "eng" or "english", "fre" or "french", "ger" or "german", "chi" or "chinese", "schi" or "simplified chinese", "rus" or "russian"
Default-Lang: "eng"

# Enable Commands
bal-Command: true
addmoney-Command: true
takemoney-Command: true
pay-Command: true
topmoney-Command: true

# Enable EconomyPlus Shop!
EnableShop: true
# Shop Format:
# [Shop]
# ItemId
# Amount
# Price

#Select a custom prefix for EconomyPlus shop signs (use @ to replace §)
ShopPrefix: '@7[@aShop@7]'

# Enable EconomyPlus Sell!
EnableSell: true
# Sell Format:
# [Sell]
# ItemId
# Amount
# Price

#Select a custom prefix for EconomyPlus sell signs (use @ to replace §)
SellPrefix: '@7[@bSell@7]'

#Enable EconomyPlus PermissionShop!
EnablePermShop: true
# Format:
# [Perm]
# Price
# Perm
# Perm

#Select a custom prefix for EconomyPlus shop signs (use @ to replace §)
PermPrefix: '@7[@cPerm@7]'

# Don't edit anything below this line it may break the plugin!
Version: 1
AccountsImported: false
...
```

#API for Developers

##Accessing the EconomyPlus API

Below is an example class using the EconomyPlus API to check a players money!

```php
<?php
namespace MyPlugin;

use EconomyPlus\EconomyPlus;

class MyClass extends \pocketmine\plugin\PluginBase{
   

   public function sendMoneyPopup(\pocketmine\Player $player)
   {
     $player->sendPopup(EconomyPlus::getInstance()->getMoney($player));
   }
}
?>
```
When passing variables to methods within the API you can pass a pocketmine\Player, String, or an EconomyPlayer!

##API Methods

| Method | Argument | Output |
| :---: | :---: | :---: |
| `addMoney()`  | `pocketmine\Player`, `int`| `bool` |
| `getMoney()`  | `pocketmine\Player`| `int` |
| `setMoney()`  | `pocketmine\Player`, `int`| `null` |
| `reduceMoney()`  | `pocketmine\Player`, `int`| `null` |
