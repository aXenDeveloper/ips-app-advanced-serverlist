![Header image](https://files.axendev.net/projects/ips/applications/serverlist/1.png)

# (aXen) Advanced Server List

Server table showing server information about the status, name, number of players, current map and their owners as applications on IPS Community Suite.

### Other features:

- Set your own fields for the server:
  - Name,
  - Own name,
  - IP,
  - Own IP,
  - Statistics URL,
  - TV URL,
  - Vote URL,
  - Forum URL
- Highlight for the server,
- Special badge for the new server,
- Hiding server owners in button,
- The scroll that the user can control,
- Color filling of players,
- RWD,
- Lazy loading in img icons,
- Table in 2 columns that the user can control,
- Debug mode for server (A reason can be enter),
- Separate page for server list,
- Refresh data servers button in AdminCP,
- Custom connect URL to the server

## 🏷️ Requirements

- [IPS Community Suite: 4.6 or 4.7 version](https://invisioncommunity.com/)
- PHP 7.4+ - For IPS 4.6
- PHP 8.0+ - For IPS 4.7
- [Bzip2](https://www.php.net/manual/en/book.bzip2.php) - Used for A2S Compressed responses,
- ❗❗ Unlocked external ports on the website server (UDP)

## 💻 Compatible servers

- America's Army 3
- America's Army: Proving Grounds
- ARK: Survival Evolved
- Arma3
- Armed Assault 2: Operation Arrowhead
- All-Seeing Eye
- Atlas
- Avorion
- Barotrauma
- Battalion 1944
- Battlefield 2
- Battlefield 3
- Battlefield 4
- Battlefield 1942
- Battlefield Bad Company 2
- Battlefield Hardline
- Black Mesa
- Brink
- Citadel
- Call of Duty
- Call of Duty 2
- Call of Duty 4
- Call of Duty: United Offensive
- Call of Duty: World at War
- Conan Exiles
- Contagion
- Crysis
- Crysis 2
- Crysis Wars
- Counter-Strike 2d
- Counter-Strike 1.5
- Counter-Strike 1.6
- Counter-Strike: Condition Zero
- Counter-Strike: Global Offensive
- Counter-Strike: Source
- Dark and Light
- DayZ Standalone
- DayZ Mod
- Discord
- Day of Defeat
- Day of Defeat: Source
- Doom 3
- Days of War
- ECO Global Survival
- Empyrion - Galactic Survival
- Wolfenstein Enemy Territory
- Enemy Territory Quake Wars
- Fortress Forever
- Frontlines Fuel of War
- Fistful of Frags
- GameSpy Server
- GameSpy2 Server
- GameSpy3 Server
- Garry's Mod
- GRAV Online
- GTA: Five M
- GTA: San Andreas Multiplayer
- Grand Theft Auto Rage
- Grand Theft Auto Network
- Hidden & Dangerous 2
- Halo: Combat Evolved
- Half Life
- Half Life 2: Deathmatch
- Hell Let Loose
- Hurtworld
- Insurgency
- Insurgency: Sandstorm
- Star Wars Jedi Knight: Jedi Academy
- Star Wars Jedi Knight II: Jedi Outcast
- Just Cause 2 Multiplayer
- Just Cause 3
- Killing Floor
- Killing Floor 2
- Kingpin: Life of Crime
- Left 4 Dead
- Left 4 Dead 2
- Minecraft
- MinecraftPE
- Miscreated
- Modiverse
- Medal of honor: Allied Assault
- MORDHAU
- Multi Theft Auto
- Mumble Server
- No More Room in Hell
- Natural Selection 2
- Open Fortress
- PixARK
- Post Scriptum
- Project Reality: Battlefield 2
- Quake 2
- Quake 3
- Quake 4
- Quake Live
- Red Orchestra 2
- Red Orchestra: Ostfront 41-45
- rFactor 2
- Rising Storm 2
- Rust
- San Andreas Multiplayer
- Sven Co-op
- Serious Sam
- 7 Days to Die
- The Ship
- Solder of Fortune II
- Soldat
- Space Engineers
- Squad
- StarMade
- Stormworks
- SWAT 4
- Teamspeak 2
- Teamspeak 3
- Teeworlds Server
- Terraria
- Team Fortress Classic
- Team Fortress 2
- Team Fortress 2 Classic
- The Forrest
- Tibia
- Tshock
- Unreal 2
- Unturned
- Unreal Tournament
- Unreal Tournament 3
- Unreal Tournament 2004
- Valheim
- V Rising
- Ventrilo
- Warsow
- World Opponent Network
- Wurm Unlimited
- Project Zomboid

## 🧰 Install

1. Go to: AdminCP -> System -> SITE FEATURES -> **Appliactions**,  
   ![Select Plugin](https://files.axendev.net/github/app/admincp_select.png)
2. Click on the link **manual upload**,  
   ![Manual Upload](https://files.axendev.net/github/app/manual_upload.png)
3. Select file **.tar** [from packet](https://github.com/aXenDeveloper/ips-app-advanced-serverlist/releases) and click install button

## 🔨 Configuration

### Mods

1. Go to: AdminCP -> Community -> Advanced Server List -> Mods -> **Create new**,
2. Provide a name mod in **Name**,
3. Choose a protocol mod in **Protocol**,
4. Click **save**.

### Servers

1. Go to: AdminCP -> Community -> Advanced Server List -> Servers -> **Create new**,
2. First you have to choice mod in **Mod**,
3. Provide a custom name in **Custom name** for example: _JailBreak_,
4. Provide a address IP in **IP** for example: **145.239.16.78:27015**.  
   ❗❗ Some servers like **TeamSpeak 3** require a **Query port**!
5. Click **save**.

### Discord configuration

You have to configurate Discord Widget which will return a value:

- name,
- instant_invite,
- presence_count

To add a discord server you have to enter the **widget ID** in the **IP** field.

If you want check return values from your server discord check from json file: `https://discordapp.com/api/guilds/{your ID widget form discord}/widget.json` for example: https://discordapp.com/api/guilds/720054040116854835/widget.json

### TeamSpeak 3 Permissions

If after adding TeamSpeak 3 server but it's still offline then check permissions for **guest** group:

```
b_virtualserver_info_view
b_virtualserver_channelgroup_list
b_virtualserver_client_list
b_virtualserver_channel_list
```

## 🔧 Custom API

From version _2.0.0_, the table supports your own custom API. To activate your custom API follow the instructions:

1. Go to: AdminCP -> Community -> Advanced Server List -> Mods -> **Create new**,
2. Provide a name mod in **Name**,
3. Choose a **Custom API**,
4. Provide address URL in **Address URL** for example: _https://query.li/api/csgo/{ip}/{port}_,
5. Provide a fields form your Custom API.  
   If your API return JSON for example:
   ```
   {
      data: {
         "status": 1
      }
   }
   ```
   then your **field** equal **status**,
6. Click **save**.

## 🛠️ Update

1. Go to: AdminCP -> System -> SITE FEATURES -> **Appliactions**,  
   ![Select Plugin](https://files.axendev.net/github/app/admincp_select.png)
2. Search appliaction and click **Upload a new version**,  
   ![Upload a new version](https://files.axendev.net/github/app/new_version_upload.png)
3. Select file **.tar** from packet and click install button.

## 📷 Graphics

![2](https://files.axendev.net/projects/ips/applications/serverlist/2.png)
![3](https://files.axendev.net/projects/ips/applications/serverlist/3.png)
![4](https://files.axendev.net/projects/ips/applications/serverlist/4.png)
![5](https://files.axendev.net/projects/ips/applications/serverlist/5.png)
![6](https://files.axendev.net/projects/ips/applications/serverlist/6.png)
![7](https://files.axendev.net/projects/ips/applications/serverlist/7.png)
![8](https://files.axendev.net/projects/ips/applications/serverlist/8.png)
![9](https://files.axendev.net/projects/ips/applications/serverlist/9.png)
![10](https://files.axendev.net/projects/ips/applications/serverlist/10.png)
![11](https://files.axendev.net/projects/ips/applications/serverlist/11.png)
![12](https://files.axendev.net/projects/ips/applications/serverlist/12.png)

## 🔌 Download from other sources

- [invisioncommunity.com](https://invisioncommunity.com/files/file/9852-axen-advanced-server-list/),
- [invisionize.pl](https://forum.invisionize.pl/files/file/825-axen-advanced-server-list/)

_The resources from the links above are updated on an ongoing basis if the administration approves the file._
