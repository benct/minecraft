<?php
/**
 * Minecraft Player Statistics and Achievements.
 *
 * Parsing of Minecraft player statistics files.
 *
 * @author      Ben Christopher Tomlin <ben@tomlin.no>
 * @copyright   Copyright (c) 2016 [bct]productions (http://tomlin.no)
 * @license     The MIT License (MIT)
 * @version     1.0 (May 2016)
 */

namespace bct;

class MCStats
{
    private $data;

    private $biomes = array(
        "Extreme Hills M","Cold Taiga M","Desert","Deep Ocean","Taiga",
        "Extreme Hills","Ice Mountains","FrozenOcean","Jungle","Extreme Hills Edge",
        "Savanna Plateau","Birch Forest Hills","DesertHills","MushroomIsland",
        "Birch Forest","Mesa","Mega Taiga","Savanna M","Savanna","River",
        "Swampland","Sunflower Plains","Extreme Hills+","Mesa Plateau F",
        "Flower Forest","Ocean","Ice Plains Spikes","Mega Taiga Hills","TaigaHills",
        "Plains","Ice Plains","FrozenRiver","MushroomIslandShore","Hell",
        "ForestHills","Cold Taiga","Forest","JungleHills","Beach","Roofed Forest",
        "Cold Beach","Cold Taiga Hills","Mesa Plateau","Stone Beach","JungleEdge",
        "Mesa (Bryce)","Sky"
    );

    /**
     * MCStats constructor. Parses a json structure containing players stats.
     * @param string $json Player data in json format
     */
    public function __construct($json)
    {
        $this->data = json_decode($json, true);
    }

    /**
     * @return array List of general player statistics
     */
    public function general()
    {
        return array(
            'play' => array(
                'name'  => 'Time Played',
                'raw'   => isset($this->data['stat.playOneMinute']) ? $this->data['stat.playOneMinute'] : 0,
                'value' => isset($this->data['stat.playOneMinute']) ? (number_format($this->data['stat.playOneMinute']/20/60/60, 2) . ' h') : '0 h',
            ),
            'walk' => array(
                'name'  => 'Distance Walked',
                'raw'   => isset($this->data['stat.walkOneCm']) ? $this->data['stat.walkOneCm'] : 0,
                'value' => isset($this->data['stat.walkOneCm']) ? (number_format($this->data['stat.walkOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'swim' => array(
                'name'  => 'Distance Swum',
                'raw'   => isset($this->data['stat.swimOneCm']) ? $this->data['stat.swimOneCm'] : 0,
                'value' => isset($this->data['stat.swimOneCm']) ? (number_format($this->data['stat.swimOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'fall' => array(
                'name'  => 'Distance Fallen',
                'raw'   => isset($this->data['stat.fallOneCm']) ? $this->data['stat.fallOneCm'] : 0,
                'value' => isset($this->data['stat.fallOneCm']) ? (number_format($this->data['stat.fallOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'climb' => array(
                'name'  => 'Distance Climbed',
                'raw'   => isset($this->data['stat.climbOneCm']) ? $this->data['stat.climbOneCm'] : 0,
                'value' => isset($this->data['stat.climbOneCm']) ? (number_format($this->data['stat.climbOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'fly' => array(
                'name'  => 'Distance Flown',
                'raw'   => isset($this->data['stat.flyOneCm']) ? $this->data['stat.flyOneCm'] : 0,
                'value' => isset($this->data['stat.flyOneCm']) ? (number_format($this->data['stat.flyOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'dive' => array(
                'name'  => 'Distance Dove',
                'raw'   => isset($this->data['stat.diveOneCm']) ? $this->data['stat.diveOneCm'] : 0,
                'value' => isset($this->data['stat.diveOneCm']) ? (number_format($this->data['stat.diveOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'cart' => array(
                'name'  => 'Distance by Minecart',
                'raw'   => isset($this->data['stat.minecartOneCm']) ? $this->data['stat.minecartOneCm'] : 0,
                'value' => isset($this->data['stat.minecartOneCm']) ? (number_format($this->data['stat.minecartOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'boat' => array(
                'name'  => 'Distance by Boat',
                'raw'   => isset($this->data['stat.boatOneCm']) ? $this->data['stat.boatOneCm'] : 0,
                'value' => isset($this->data['stat.boatOneCm']) ? (number_format($this->data['stat.boatOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'pig' => array(
                'name'  => 'Distance by Pig',
                'raw'   => isset($this->data['stat.pigOneCm']) ? $this->data['stat.pigOneCm'] : 0,
                'value' => isset($this->data['stat.pigOneCm']) ? (number_format($this->data['stat.pigOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'horse' => array(
                'name'  => 'Distance by Horse',
                'raw'   => isset($this->data['stat.horseOneCm']) ? $this->data['stat.horseOneCm'] : 0,
                'value' => isset($this->data['stat.horseOneCm']) ? (number_format($this->data['stat.horseOneCm']/100000, 2) . ' km') : '0 km',
            ),
            'jump' => array(
                'name'  => 'Jumps',
                'raw'   => isset($this->data['stat.jump']) ? $this->data['stat.jump'] : 0,
                'value' => isset($this->data['stat.jump']) ? number_format($this->data['stat.jump']) : 0,
            ),
            'drop' => array(
                'name'  => 'Items Dropped',
                'raw'   => isset($this->data['stat.drop']) ? $this->data['stat.drop'] : 0,
                'value' => isset($this->data['stat.drop']) ? number_format($this->data['stat.drop']) : 0,
            ),
            'damageDealt' => array(
                'name'  => 'Damage Dealt',
                'raw'   => isset($this->data['stat.damageDealt']) ? $this->data['stat.damageDealt'] : 0,
                'value' => isset($this->data['stat.damageDealt']) ? number_format($this->data['stat.damageDealt']/10) : 0,
            ),
            'damageTaken' => array(
                'name'  => 'Damage Taken',
                'raw'   => isset($this->data['stat.damageTaken']) ? $this->data['stat.damageTaken'] : 0,
                'value' => isset($this->data['stat.damageTaken']) ? number_format($this->data['stat.damageTaken']/10) : 0,
            ),
            'death' => array(
                'name'  => 'Deaths',
                'raw'   => isset($this->data['stat.deaths']) ? $this->data['stat.deaths'] : 0,
                'value' => isset($this->data['stat.deaths']) ? number_format($this->data['stat.deaths']) : 0,
            ),
            'mobkills' => array(
                'name'  => 'Mobs Killed',
                'raw'   => isset($this->data['stat.mobKills']) ? $this->data['stat.mobKills'] : 0,
                'value' => isset($this->data['stat.mobKills']) ? number_format($this->data['stat.mobKills']) : 0,
            ),
            'playerkills' => array(
                'name'  => 'Players Killed',
                'raw'   => isset($this->data['stat.playerKills']) ? $this->data['stat.playerKills'] : 0,
                'value' => isset($this->data['stat.playerKills']) ? number_format($this->data['stat.playerKills']) : 0,
            ),
            'bred' => array(
                'name'  => 'Animals Bred',
                'raw'   => isset($this->data['stat.animalsBred']) ? $this->data['stat.animalsBred'] : 0,
                'value' => isset($this->data['stat.animalsBred']) ? number_format($this->data['stat.animalsBred']) : 0,
            ),
            'fish' => array(
                'name'  => 'Fish Caught',
                'raw'   => isset($this->data['stat.fishCaught']) ? $this->data['stat.fishCaught'] : 0,
                'value' => isset($this->data['stat.fishCaught']) ? number_format($this->data['stat.fishCaught']) : 0,
            ),
            'junk' => array(
                'name'  => 'Junk Fished',
                'raw'   => isset($this->data['stat.junkFished']) ? $this->data['stat.junkFished'] : 0,
                'value' => isset($this->data['stat.junkFished']) ? number_format($this->data['stat.junkFished']) : 0,
            ),
            'treasure' => array(
                'name'  => 'Treasure Fished',
                'raw'   => isset($this->data['stat.treasureFished']) ? $this->data['stat.treasureFished'] : 0,
                'value' => isset($this->data['stat.treasureFished']) ? number_format($this->data['stat.treasureFished']) : 0,
            ),
        );
    }

    /**
     * @return array List of player achievements
     */
    public function achievements()
    {
        return array(
            'openInventory' => array(
                'name'  => 'Taking Inventory',
                'has'   => isset($this->data['achievement.openInventory']),
                'value' => isset($this->data['achievement.openInventory']) ? $this->data['achievement.openInventory'] : 0,
                'image' => 'openInventory.png'
            ),
            'mineWood' => array(
                'name'  => 'Getting Wood',
                'has'   => isset($this->data['achievement.mineWood']),
                'value' => isset($this->data['achievement.mineWood']) ? $this->data['achievement.mineWood'] : 0,
                'image' => 'mineWood.png'
            ),
            'buildWorkBench' => array(
                'name'  => 'Benchmarking',
                'has'   => isset($this->data['achievement.buildWorkBench']),
                'value' => isset($this->data['achievement.buildWorkBench']) ? $this->data['achievement.buildWorkBench'] : 0,
                'image' => 'buildWorkBench.png'
            ),
            'buildPickaxe' => array(
                'name'  => 'Time to Mine!',
                'has'   => isset($this->data['achievement.buildPickaxe']),
                'value' => isset($this->data['achievement.buildPickaxe']) ? $this->data['achievement.buildPickaxe'] : 0,
                'image' => 'buildPickaxe.png'
            ),
            'buildFurnace' => array(
                'name'  => 'Hot Topic',
                'has'   => isset($this->data['achievement.buildFurnace']),
                'value' => isset($this->data['achievement.buildFurnace']) ? $this->data['achievement.buildFurnace'] : 0,
                'image' => 'buildFurnace.png'
            ),
            'acquireIron' => array(
                'name'  => 'Acquire Hardware',
                'has'   => isset($this->data['achievement.acquireIron']),
                'value' => isset($this->data['achievement.acquireIron']) ? $this->data['achievement.acquireIron'] : 0,
                'image' => 'acquireIron.png'
            ),
            'buildHoe' => array(
                'name'  => 'Time to Farm!',
                'has'   => isset($this->data['achievement.buildHoe']),
                'value' => isset($this->data['achievement.buildHoe']) ? $this->data['achievement.buildHoe'] : 0,
                'image' => 'buildHoe.png'
            ),
            'makeBread' => array(
                'name'  => 'Bake Bread',
                'has'   => isset($this->data['achievement.makeBread']),
                'value' => isset($this->data['achievement.makeBread']) ? $this->data['achievement.makeBread'] : 0,
                'image' => 'makeBread.png'
            ),
            'bakeCake' => array(
                'name'  => 'The Lie',
                'has'   => isset($this->data['achievement.bakeCake']),
                'value' => isset($this->data['achievement.bakeCake']) ? $this->data['achievement.bakeCake'] : 0,
                'image' => 'bakeCake.png'
            ),
            'buildBetterPickaxe' => array(
                'name'  => 'Getting an Upgrade',
                'has'   => isset($this->data['achievement.buildBetterPickaxe']),
                'value' => isset($this->data['achievement.buildBetterPickaxe']) ? $this->data['achievement.buildBetterPickaxe'] : 0,
                'image' => 'buildBetterPickaxe.png'
            ),
            'cookFish' => array(
                'name'  => 'Delicious Fish',
                'has'   => isset($this->data['achievement.cookFish']),
                'value' => isset($this->data['achievement.cookFish']) ? $this->data['achievement.cookFish'] : 0,
                'image' => 'cookFish.png'
            ),
            'onARail' => array(
                'name'  => 'On A Rail',
                'has'   => isset($this->data['achievement.onARail']),
                'value' => isset($this->data['achievement.onARail']) ? $this->data['achievement.onARail'] : 0,
                'image' => 'onARail.png'
            ),
            'buildSword' => array(
                'name'  => 'Time to Strike!',
                'has'   => isset($this->data['achievement.buildSword']),
                'value' => isset($this->data['achievement.buildSword']) ? $this->data['achievement.buildSword'] : 0,
                'image' => 'buildSword.png'
            ),
            'killEnemy' => array(
                'name'  => 'Monster Hunter',
                'has'   => isset($this->data['achievement.killEnemy']),
                'value' => isset($this->data['achievement.killEnemy']) ? $this->data['achievement.killEnemy'] : 0,
                'image' => 'killEnemy.png'
            ),
            'killCow' => array(
                'name'  => 'Cow Tipper',
                'has'   => isset($this->data['achievement.killCow']),
                'value' => isset($this->data['achievement.killCow']) ? $this->data['achievement.killCow'] : 0,
                'image' => 'killCow.png'
            ),
            'flyPig' => array(
                'name'  => 'When Pigs Fly',
                'has'   => isset($this->data['achievement.flyPig']),
                'value' => isset($this->data['achievement.flyPig']) ? $this->data['achievement.flyPig'] : 0,
                'image' => 'flyPig.png'
            ),
            'snipeSkeleton' => array(
                'name'  => 'Sniper Duel',
                'has'   => isset($this->data['achievement.snipeSkeleton']),
                'value' => isset($this->data['achievement.snipeSkeleton']) ? $this->data['achievement.snipeSkeleton'] : 0,
                'image' => 'snipeSkeleton.png'
            ),
            'diamonds' => array(
                'name'  => 'DIAMONDS!',
                'has'   => isset($this->data['achievement.diamonds']),
                'value' => isset($this->data['achievement.diamonds']) ? $this->data['achievement.diamonds'] : 0,
                'image' => 'diamonds.png'
            ),
            'portal' => array(
                'name'  => 'We Need to Go Deeper',
                'has'   => isset($this->data['achievement.portal']),
                'value' => isset($this->data['achievement.portal']) ? $this->data['achievement.portal'] : 0,
                'image' => 'portal.png'
            ),
            'ghast' => array(
                'name'  => 'Return to Sender',
                'has'   => isset($this->data['achievement.ghast']),
                'value' => isset($this->data['achievement.ghast']) ? $this->data['achievement.ghast'] : 0,
                'image' => 'ghast.png'
            ),
            'blazeRod' => array(
                'name'  => 'Into Fire',
                'has'   => isset($this->data['achievement.blazeRod']),
                'value' => isset($this->data['achievement.blazeRod']) ? $this->data['achievement.blazeRod'] : 0,
                'image' => 'blazeRod.png'
            ),
            'potion' => array(
                'name'  => 'Local Brewery',
                'has'   => isset($this->data['achievement.potion']),
                'value' => isset($this->data['achievement.potion']) ? $this->data['achievement.potion'] : 0,
                'image' => 'potion.png'
            ),
            'theEnd' => array(
                'name'  => 'The End?',
                'has'   => isset($this->data['achievement.theEnd']),
                'value' => isset($this->data['achievement.theEnd']) ? $this->data['achievement.theEnd'] : 0,
                'image' => 'theEnd.png'
            ),
            'theEnd2' => array(
                'name'  => 'The End.',
                'has'   => isset($this->data['achievement.theEnd2']),
                'value' => isset($this->data['achievement.theEnd2']) ? $this->data['achievement.theEnd2'] : 0,
                'image' => 'theEnd2.png'
            ),
            'enchantments' => array(
                'name'  => 'Enchanter',
                'has'   => isset($this->data['achievement.enchantments']),
                'value' => isset($this->data['achievement.enchantments']) ? $this->data['achievement.enchantments'] : 0,
                'image' => 'enchantments.png'
            ),
            'overkill' => array(
                'name'  => 'Overkill',
                'has'   => isset($this->data['achievement.overkill']),
                'value' => isset($this->data['achievement.overkill']) ? $this->data['achievement.overkill'] : 0,
                'image' => 'overkill.png'
            ),
            'bookcase' => array(
                'name'  => 'Librarian',
                'has'   => isset($this->data['achievement.bookcase']),
                'value' => isset($this->data['achievement.bookcase']) ? $this->data['achievement.bookcase'] : 0,
                'image' => 'bookcase.png'
            ),
            'exploreAllBiomes' => array(
                'name'  => 'Adventuring Time',
                'has'   => isset($this->data['achievement.exploreAllBiomes']) && $this->data['achievement.exploreAllBiomes']['value'],
                'value' => isset($this->data['achievement.exploreAllBiomes']) ? count($this->data['achievement.exploreAllBiomes']['progress']) : 0,
                'image' => 'exploreAllBiomes.png'
            ),
            'spawnWither' => array(
                'name'  => 'The Beginning?',
                'has'   => isset($this->data['achievement.spawnWither']),
                'value' => isset($this->data['achievement.spawnWither']) ? $this->data['achievement.spawnWither'] : 0,
                'image' => 'spawnWither.png'
            ),
            'killWither' => array(
                'name'  => 'The Beginning.',
                'has'   => isset($this->data['achievement.killWither']),
                'value' => isset($this->data['achievement.killWither']) ? $this->data['achievement.killWither'] : 0,
                'image' => 'killWither.png'
            ),
            'fullBeacon' => array(
                'name'  => 'Beaconator',
                'has'   => isset($this->data['achievement.fullBeacon']),
                'value' => isset($this->data['achievement.fullBeacon']) ? $this->data['achievement.fullBeacon'] : 0,
                'image' => 'fullBeacon.png'
            ),
            'breedCow' => array(
                'name'  => 'Repopulation',
                'has'   => isset($this->data['achievement.breedCow']),
                'value' => isset($this->data['achievement.breedCow']) ? $this->data['achievement.breedCow'] : 0,
                'image' => 'breedCow.png'
            ),
            'diamondsToYou' => array(
                'name'  => 'Diamonds to you!',
                'has'   => isset($this->data['achievement.diamondsToYou']),
                'value' => isset($this->data['achievement.diamondsToYou']) ? $this->data['achievement.diamondsToYou'] : 0,
                'image' => 'diamondsToYou.png'
            ),
            'overpowered' => array(
                'name'  => 'Overpowered',
                'has'   => isset($this->data['achievement.overpowered']),
                'value' => isset($this->data['achievement.overpowered']) ? $this->data['achievement.overpowered'] : 0,
                'image' => 'overpowered.png'
            ),
        );
    }

    /**
     * @return array List of biomes that have been explored.
     */
    public function explored()
    {
        return $this->data['achievement.exploreAllBiomes']['progress'];
    }

    /**
     * @return array List of biomes that have not been explored.
     */
    public function unexplored()
    {
        return array_diff($this->biomes, $this->data['achievement.exploreAllBiomes']['progress']);
    }

    /**
     * @return array List of killed mobs statistics.
     */
    public function killed()
    {
        return array(
            'enderman' => array(
                'name'  => 'Endermen',
                'raw'   => isset($this->data['stat.killEntity.Enderman']) ? $this->data['stat.killEntity.Enderman'] : 0,
                'value' => isset($this->data['stat.killEntity.Enderman']) ? number_format($this->data['stat.killEntity.Enderman']) : 0,
            ),
            'villager' => array(
                'name'  => 'Villagers',
                'raw'   => isset($this->data['stat.killEntity.Villager']) ? $this->data['stat.killEntity.Villager'] : 0,
                'value' => isset($this->data['stat.killEntity.Villager']) ? number_format($this->data['stat.killEntity.Villager']) : 0,
            ),
            'creeper' => array(
                'name'  => 'Creepers',
                'raw'   => isset($this->data['stat.killEntity.Creeper']) ? $this->data['stat.killEntity.Creeper'] : 0,
                'value' => isset($this->data['stat.killEntity.Creeper']) ? number_format($this->data['stat.killEntity.Creeper']) : 0,
            ),
            'squid' => array(
                'name'  => 'Squid',
                'raw'   => isset($this->data['stat.killEntity.Squid']) ? $this->data['stat.killEntity.Squid'] : 0,
                'value' => isset($this->data['stat.killEntity.Squid']) ? number_format($this->data['stat.killEntity.Squid']) : 0,
            ),
            'zombie' => array(
                'name'  => 'Zombies',
                'raw'   => isset($this->data['stat.killEntity.Zombie']) ? $this->data['stat.killEntity.Zombie'] : 0,
                'value' => isset($this->data['stat.killEntity.Zombie']) ? number_format($this->data['stat.killEntity.Zombie']) : 0,
            ),
            'blaze' => array(
                'name'  => 'Blaze',
                'raw'   => isset($this->data['stat.killEntity.Blaze']) ? $this->data['stat.killEntity.Blaze'] : 0,
                'value' => isset($this->data['stat.killEntity.Blaze']) ? number_format($this->data['stat.killEntity.Blaze']) : 0,
            ),
            'sheep' => array(
                'name'  => 'Sheep',
                'raw'   => isset($this->data['stat.killEntity.Sheep']) ? $this->data['stat.killEntity.Sheep'] : 0,
                'value' => isset($this->data['stat.killEntity.Sheep']) ? number_format($this->data['stat.killEntity.Sheep']) : 0,
            ),
            'slime' => array(
                'name'  => 'Slimes',
                'raw'   => isset($this->data['stat.killEntity.Slime']) ? $this->data['stat.killEntity.Slime'] : 0,
                'value' => isset($this->data['stat.killEntity.Slime']) ? number_format($this->data['stat.killEntity.Slime']) : 0,
            ),
            'cavespider' => array(
                'name'  => 'CaveSpiders',
                'raw'   => isset($this->data['stat.killEntity.CaveSpider']) ? $this->data['stat.killEntity.CaveSpider'] : 0,
                'value' => isset($this->data['stat.killEntity.CaveSpider']) ? number_format($this->data['stat.killEntity.CaveSpider']) : 0,
            ),
            'chicken' => array(
                'name'  => 'Chicken',
                'raw'   => isset($this->data['stat.killEntity.Chicken']) ? $this->data['stat.killEntity.Chicken'] : 0,
                'value' => isset($this->data['stat.killEntity.Chicken']) ? number_format($this->data['stat.killEntity.Chicken']) : 0,
            ),
            'horse' => array(
                'name'  => 'Horses',
                'raw'   => isset($this->data['stat.killEntity.EntityHorse']) ? $this->data['stat.killEntity.EntityHorse'] : 0,
                'value' => isset($this->data['stat.killEntity.EntityHorse']) ? number_format($this->data['stat.killEntity.EntityHorse']) : 0,
            ),
            'pigzombie' => array(
                'name'  => 'Zombie Pigmen',
                'raw'   => isset($this->data['stat.killEntity.PigZombie']) ? $this->data['stat.killEntity.PigZombie'] : 0,
                'value' => isset($this->data['stat.killEntity.PigZombie']) ? number_format($this->data['stat.killEntity.PigZombie']) : 0,
            ),
            'witch' => array(
                'name'  => 'Witches',
                'raw'   => isset($this->data['stat.killEntity.Witch']) ? $this->data['stat.killEntity.Witch'] : 0,
                'value' => isset($this->data['stat.killEntity.Witch']) ? number_format($this->data['stat.killEntity.Witch']) : 0,
            ),
            'mushroomcow' => array(
                'name'  => 'Mooshrooms',
                'raw'   => isset($this->data['stat.killEntity.MushroomCow']) ? $this->data['stat.killEntity.MushroomCow'] : 0,
                'value' => isset($this->data['stat.killEntity.MushroomCow']) ? number_format($this->data['stat.killEntity.MushroomCow']) : 0,
            ),
            'ghast' => array(
                'name'  => 'Ghasts',
                'raw'   => isset($this->data['stat.killEntity.Ghast']) ? $this->data['stat.killEntity.Ghast'] : 0,
                'value' => isset($this->data['stat.killEntity.Ghast']) ? number_format($this->data['stat.killEntity.Ghast']) : 0,
            ),
            'bat' => array(
                'name'  => 'Bats',
                'raw'   => isset($this->data['stat.killEntity.Bat']) ? $this->data['stat.killEntity.Bat'] : 0,
                'value' => isset($this->data['stat.killEntity.Bat']) ? number_format($this->data['stat.killEntity.Bat']) : 0,
            ),
            'guardian' => array(
                'name'  => 'Guardians',
                'raw'   => isset($this->data['stat.killEntity.Guardian']) ? $this->data['stat.killEntity.Guardian'] : 0,
                'value' => isset($this->data['stat.killEntity.Guardian']) ? number_format($this->data['stat.killEntity.Guardian']) : 0,
            ),
            'shulker' => array(
                'name'  => 'Shulkers',
                'raw'   => isset($this->data['stat.killEntity.Shulker']) ? $this->data['stat.killEntity.Shulker'] : 0,
                'value' => isset($this->data['stat.killEntity.Shulker']) ? number_format($this->data['stat.killEntity.Shulker']) : 0,
            ),
            'silverfish' => array(
                'name'  => 'Silverfish',
                'raw'   => isset($this->data['stat.killEntity.Silverfish']) ? $this->data['stat.killEntity.Silverfish'] : 0,
                'value' => isset($this->data['stat.killEntity.Silverfish']) ? number_format($this->data['stat.killEntity.Silverfish']) : 0,
            ),
            'ozelot' => array(
                'name'  => 'Ozelots',
                'raw'   => isset($this->data['stat.killEntity.Ozelot']) ? $this->data['stat.killEntity.Ozelot'] : 0,
                'value' => isset($this->data['stat.killEntity.Ozelot']) ? number_format($this->data['stat.killEntity.Ozelot']) : 0,
            ),
            'rabbit' => array(
                'name'  => 'Rabbits',
                'raw'   => isset($this->data['stat.killEntity.Rabbit']) ? $this->data['stat.killEntity.Rabbit'] : 0,
                'value' => isset($this->data['stat.killEntity.Rabbit']) ? number_format($this->data['stat.killEntity.Rabbit']) : 0,
            ),
            'lavaslime' => array(
                'name'  => 'Magma Slimes',
                'raw'   => isset($this->data['stat.killEntity.LavaSlime']) ? $this->data['stat.killEntity.LavaSlime'] : 0,
                'value' => isset($this->data['stat.killEntity.LavaSlime']) ? number_format($this->data['stat.killEntity.LavaSlime']) : 0,
            ),
            'cow' => array(
                'name'  => 'Cows',
                'raw'   => isset($this->data['stat.killEntity.Cow']) ? $this->data['stat.killEntity.Cow'] : 0,
                'value' => isset($this->data['stat.killEntity.Cow']) ? number_format($this->data['stat.killEntity.Cow']) : 0,
            ),
            'pig' => array(
                'name'  => 'Pigs',
                'raw'   => isset($this->data['stat.killEntity.Pig']) ? $this->data['stat.killEntity.Pig'] : 0,
                'value' => isset($this->data['stat.killEntity.Pig']) ? number_format($this->data['stat.killEntity.Pig']) : 0,
            ),
            'skeleton' => array(
                'name'  => 'Skeletons',
                'raw'   => isset($this->data['stat.killEntity.Skeleton']) ? $this->data['stat.killEntity.Skeleton'] : 0,
                'value' => isset($this->data['stat.killEntity.Skeleton']) ? number_format($this->data['stat.killEntity.Skeleton']) : 0,
            ),
            'spider' => array(
                'name'  => 'Spiders',
                'raw'   => isset($this->data['stat.killEntity.Spider']) ? $this->data['stat.killEntity.Spider'] : 0,
                'value' => isset($this->data['stat.killEntity.Spider']) ? number_format($this->data['stat.killEntity.Spider']) : 0,
            ),
        );
    }

    /**
     * @return array List of blocks mined statistics.
     */
    public function mined()
    {
        return array(
            'stone' => array(
                'name'  => 'Stone',
                'raw'   => isset($this->data['stat.mineBlock.minecraft.stone']) ? $this->data['stat.mineBlock.minecraft.stone'] : 0,
                'value' => isset($this->data['stat.mineBlock.minecraft.stone']) ? number_format($this->data['stat.mineBlock.minecraft.stone']) : 0,
            ),
            'dirt' => array(
                'name'  => 'Dirt',
                'raw'   => isset($this->data['stat.mineBlock.minecraft.dirt']) ? $this->data['stat.mineBlock.minecraft.dirt'] : 0,
                'value' => isset($this->data['stat.mineBlock.minecraft.dirt']) ? number_format($this->data['stat.mineBlock.minecraft.dirt']) : 0,
            ),
            // Extend with more fields as needed...
        );
    }

    /**
     * @return array List of items crafted statistics.
     */
    public function crafted()
    {
        return array(
            'diamond_pickaxe' => array(
                'name'  => 'Diamond Pickaxe',
                'raw'   => isset($this->data['stat.craftItem.minecraft.diamond_pickaxe']) ? $this->data['stat.craftItem.minecraft.diamond_pickaxe'] : 0,
                'value' => isset($this->data['stat.craftItem.minecraft.diamond_pickaxe']) ? number_format($this->data['stat.craftItem.minecraft.diamond_pickaxe']) : 0,
            ),
            // Extend with more fields as needed...
        );
    }

    /**
     * @return array List of items used statistics.
     */
    public function used()
    {
        return array(
            'diamond_pickaxe' => array(
                'name'  => 'Diamond Pickaxe',
                'raw'   => isset($this->data['stat.useItem.minecraft.diamond_pickaxe']) ? $this->data['stat.useItem.minecraft.diamond_pickaxe'] : 0,
                'value' => isset($this->data['stat.useItem.minecraft.diamond_pickaxe']) ? number_format($this->data['stat.useItem.minecraft.diamond_pickaxe']) : 0,
            ),
            // Extend with more fields as needed...
        );
    }

    /**
     * @return array List of items picked up statistics.
     */
    public function pickedup()
    {
        return array(
            'stone' => array(
                'name'  => 'Stone',
                'raw'   => isset($this->data['stat.pickup.minecraft.stone']) ? $this->data['stat.pickup.minecraft.stone'] : 0,
                'value' => isset($this->data['stat.pickup.minecraft.stone']) ? number_format($this->data['stat.pickup.minecraft.stone']) : 0,
            ),
            // Extend with more fields as needed...
        );
    }

    /**
     * @return array List of items dropped statistics.
     */
    public function dropped()
    {
        return array(
            'torch' => array(
                'name'  => 'Torch',
                'raw'   => isset($this->data['stat.drop.minecraft.torch']) ? $this->data['stat.drop.minecraft.torch'] : 0,
                'value' => isset($this->data['stat.drop.minecraft.torch']) ? number_format($this->data['stat.drop.minecraft.torch']) : 0,
            ),
            // Extend with more fields as needed...
        );
    }
}