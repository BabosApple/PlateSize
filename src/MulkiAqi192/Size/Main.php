<?php

namespace MulkiAqi192\Size;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    public function onEnable(){
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->getResource("config.yml");
        if($this->getConfig()->get("form-api-usage") == "true"){
            if(is_null($this->getServer()->getPluginManager()->getPlugin("FormAPI"))){
                $this->getLogger()->info("§cSize plugin is detected using formapi but plugin formapi is not detected, disabling plugin....");
            } else {
                $this->getLogger()->info("§aSize plugin enabled! using FormAPI");
            }
        } else {
            $this->getLogger()->info("§aSize plugin enabled! without FormAPI");
        }
        if(!$this->getConfig()->get("config-version") == "1.0"){
            $this->getLogger()->info("§cYour config file is outdated! please delete the config folder and try to reload/restart the server! disabling plugin....");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{

        switch($command->getName()){
            case "size":
                if($sender instanceof Player){
                    if($sender->hasPermission("size.use")){
                        if($this->getConfig()->get("form-api-usage") == "true"){
                            $this->sizeform($sender);
                        } else {
                            if(count($args) >= 2){
                                $sender->sendMessage("§7[§aSize§7] §cPlease use /size [your size]");
                            }
                            if(!isset($args[0])){
                                $sender->sendMessage("§7[§aSize§7] §cPlease use /size [your size]");
                            } else {
                                    if(is_numeric($args[0])){
                                        if($this->getConfig()->get("max-size") == "true"){
                                            if($args[0] > $this->getConfig()->get("max-size-number")){
                                                $sender->sendMessage("§7[§aSize§7] §cYour argument is reach the max amount of size! max size is: §e" . $this->getConfig()->get("max-size-number"));
                                            } else {
                                                $sender->setScale($args[0]);
                                                $sender->sendMessage("§7[§aSize§7] §aYour size is succesfully changed to §e" . $args[0]);
                                            }
                                        } else {
                                            $sender->setScale($args[0]);
                                            $sender->sendMessage("§7[§aSize§7] §aYour size is succesfully changed to §e" . $args[0]);
                                        }
                                    } else {
                                        $sender->sendMessage("§7[§aSize§7] §cPlease put a specific number to change your size!");
                                    }
                                }
                            }
                        }
                    }
                }
        return true;
    }

    public function sizeform($player){
        $form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function (Player $player, array $data = null){
           if($data === null){
               return true;
           }
           if(is_numeric($data[0])){
               if($this->getConfig()->get("max-size") == "true"){
                   if($data[0] > $this->getConfig()->get("max-size-number")){
                       $player->sendMessage("§7[§aSize§7] §cYour argument is reach the max amount of size! max size is: §e" . $this->getConfig()->get("max-size-number"));
                   } else {
                       $player->setScale($data[0]);
                       $player->sendMessage("§7[§aSize§7] §aYour size is succesfully changed to §e" . $data[0]);
                   }
               } else {
                   $player->setScale($data[0]);
                   $player->sendMessage("§7[§aSize§7] §aYour size is succesfully changed to §e" . $data[0]);
               }
           } else {
               $player->sendMessage("§7[§aSize§7] §cPlease put a specific number to change your size!");
           }
        });
        $form->setTitle("§aSize§6Menu");
        $form->addInput("§eType specific size you want to apply!");
        $form->sendToPlayer($player);
        return $form;
    }

}
