# PocketRPG  -  Version 0.3.0

An RPG plugin for PocketMine (and Forks(Genisys tested, Clearsky untested))

Get your latest official PocketRPG version at https://github.com/NL-4-DEVS/PocketRPG/releases !

Report bugs, questions and problems in the gitter chat or the issue tab of github.

[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/NL-4-DEVS-PocketRPG/Lobby?source=orgpage)

**This plugin depends on PurePerms to work properly!**

**Features:**

PocketRPG is made for servers to create a better adventure experience, using quests and special classes which there are 4 of. (Mage, Warrior, Assassin, Tanker) Each using different items to fight and abilities to use. This only works when RPGworld in config is defined.
use the command /rpg start <class> to begin. Using weapons and items from classes requires mana, which regenerates over time. (Hunger bar) Make sure hunger is on in your server for this to work.


Next to classes PocketRPG provides Quests which you can create, edit, start and finish in any way you'd like. Please use /quest help in game to see all quest commands. Subcommands of the /quest edit command are as follows:

    Name: changes the quest name with quest number in front of it.    

    Description: changes the description of the quest.    

    Requiredexplvl: changes the minimum experience level you need to have to start the quest.    

    Requiredid: the ID of the item you want the player to need when completing a quest.    
 
    Requiredamount: the amount of that item that is needed to complete.     

    Rewardid: the ID of the item the player gets as a finish reward.     

    Rewardamount: the amount of that item.    


Quests can only be started and finished once, so you won't have any reward cheaters!

Parties! Players in the same party won't be able to damage eachother!

**Commands:**

    /quest   
    
    /rpg    

    /party     


**Report any bugs you may find in the issues please :)**

New command: /rpg warp. Warps the player to RPG world if he/she has chosen a class already.
