<?php

declare(strict_types=1);

namespace Itzkiller10\RepairUI;

use pocketmine\level\Level;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\{Command,CommandSender};
use pocketmine\utils\Config;
use pocketmine\command\PluginIdentifiableCommand;

use onebone\economyapi\EconomyAPI;
use onebone\pointapi\PointAPI;
use pocketmine\block\Block;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Tool;
use pocketmine\item\Armor;
use pocketmine\item\Item;

class Main extends PluginBase {

    public function onEnable() {
		# World
        $this->getLogger()->info("§aEnabled");
    }


	public function runAsOp(Player $player, String $cmd){
		if ($player->isOp()) {
                    $this->getServer()->dispatchCommand($player, $cmd);
                } else {
                    $this->getServer()->addOp($player->getName());                     $this->getServer()->dispatchCommand($player, $cmd);
                    $this->getServer()->removeOp($player->getName());
                  }
              }


	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if ($sender instanceof Player and $command->getName() == "repair") {
            $this->permPage($sender);
        }
        return true;
    }




    public function permPage(Player $player) {
    
    	
        $form = new SimpleForm(function (Player $player, $data){
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0: 
							if (!$player->hasPermission("repair.money")) {
                                $player->sendMessage("§8[§c!§8]§r This is §clocked!");
                                return true;
                            }else{
								
								if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($player) >= 500){
								$item = $player->getInventory()->getItemInHand();
								if($item instanceof Armor or $item instanceof Tool){
								$id = $item->getId();
								$meta = $item->getDamage();
								$player->getInventory()->removeItem(Item::get($id, $meta, 1));
								$newitem = Item::get($id, 0, 1);
								if($item->hasCustomName()){
									$newitem->setCustomName($item->getCustomName());
								}
								if($item->hasEnchantments()){
									foreach($item->getEnchantments() as $enchants){
										$newitem->addEnchantment($enchants);
									}
								}
								$player->getInventory()->addItem($newitem);
								$player->sendMessage("§8[§a!§8]§r The§a " . $item->getName() . " §rhas been repaired");
								EconomyAPI::getInstance()->reduceMoney($player, 500);
							  return true;
							} else {
								$player->sendMessage("§8[§c!§8] §rPlease hold §citem§r in your hand!");
								return false;
							}
							return true;
							} else {
								$player->sendMessage("§8[§c!§8]§r You need§c $3000 §rto repair your weapons-tools!!!");
							}		
						}
				case 1: 
							if (!$player->hasPermission("repair.money")) {
                                $player->sendMessage("§8[§c!§8]§r This is §clocked!");
                                return true;
                            }else{
								
								if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("TokenAPI")->myPoint($player) >= 30){
								$item = $player->getInventory()->getItemInHand();
								if($item instanceof Armor or $item instanceof Tool){
								$id = $item->getId();
								$meta = $item->getDamage();
								$player->getInventory()->removeItem(Item::get($id, $meta, 1));
								$newitem = Item::get($id, 0, 1);
								if($item->hasCustomName()){
									$newitem->setCustomName($item->getCustomName());
								}
								if($item->hasEnchantments()){
									foreach($item->getEnchantments() as $enchants){
										$newitem->addEnchantment($enchants);
									}
								}
								$player->getInventory()->addItem($newitem);
								$player->sendMessage("§8[§a!§8]§r The§a " . $item->getName() . " §rhas been repaired");
								EconomyAPI::getInstance()->reduceMoney($player, 30);
							  return true;
							} else {
								$player->sendMessage("§8[§c!§8] §rPlease hold §citem§r in your hand!");
								return false;
							}
							return true;
							} else {
								$player->sendMessage("§8[§c!§8]§r You need§c 30 tokens §rto repair your weapons-tools!!!");
							}		
						}						
						
				
							
            }
            }
        );
        $form->setTitle("§l§dREPAIR SYSTEM");
		$money = \onebone\economyapi\EconomyAPI::getInstance()->myMoney($player);
		$tokens = \onebone\pointapi\PointAPI::getInstance()->myPoint($player);
        $form->setContent("Your money§8:§e $money$\nYour tokens§8:§e $tokens");
        $form->addButton($player->hasPermission("repair.money") === true ? "Repair Hand\nCost 500$" : "Repair Hand\nCost 500$");
		$form->addButton($player->hasPermission("repair.money") === true ? "Repair Hand\nCost 250 tokens" : "Repair Hand\nCost 250 tokens"); 
		
            
        $form->addButton("§cClose");
        $form->sendToPlayer($player);
    }

    
}