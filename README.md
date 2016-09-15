# EconomyPlus

EconomyPlus is an light Economy plugin built for PocketMine-MP (and all PHP7 alts) with many features!

#Commands

| Command | Argument | Description |
| :-: | :---------: | :---------------: | :---------: |
| /addmoney | `<player> <amount>` | Add Money to an Player! |
| /bal | NA | View your money balance!
| /pay | `<player> <amount>` | Pay an Player!
| /takemoney | `<player> <amount>` | Take Money from an Player! |
| /topmoney | NA | See the TopMoney Stats! |

#Shops

##Sell Shop

| Line1 | Line2 | Line3 | Line4 |
| :---: | :---: | :---: | :---: |
| [Shop] | `<itemid>` | `<amount>` | `<price>` |

##Buy Shops

| Line1 | Line2 | Line3 | Line4 |
| :---: | :---: | :---: | :---: |
| [Shop] | `<itemid>` | `<amount>` | `<price>` |

##Permission Shops

| Line1 | Line2 | Line3 | Line4 |
| :---: | :---: | :---: | :---: |
| [Perm] | `<price>` | `<perm>` | `<perm cont.>` |

#API for Developers

##Accessing the EconomyPlus API

Below is an example class using the EconomyPlus API to check a players money!

```php
<php
namespace MyPlugin\MyClass;

use EconomyPlus\Main;

class MyClass extends \pocketmine\plugin\PluginBase{
   

   public function sendMoneyPopup(\pocketmine\Player $player)
   {
     $player->sendPopup(Main::getInstance()->getMoney($player));
   }
}
?>
```

When passing variables to functions within the API you can pass a pocketmine\Player, String, or an EconomyPlayer!
