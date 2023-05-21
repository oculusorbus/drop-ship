import {Blockfrost, Lucid} from "https://unpkg.com/lucid-cardano@0.8.7/web/mod.js";
window.Lucid=Lucid;

async function connectWallet(wallet){
	if(wallet != "none"){
		document.getElementById('loading').style.display = "block";
		const lucid = await Lucid.new(
		        new Blockfrost("https://mainnet.blockfrost.io/api/v0", "mainnetn6TwLzWl4yFlbMUnKN9rOueczD7dOXgo"),
		        "Mainnet",
		);
		//var wallet = "nami";
		const the_wallet = window.cardano[wallet];
		const api = await the_wallet.enable();
		lucid.selectWallet(api);
		const address = await lucid.wallet.address();
		const stakeAddress = await lucid.wallet.rewardAddress();
		/* Old address approach
		if(address != ""){
			sendAddress(address, wallet);
		}*/
		if(stakeAddress != ""){
			sendAddress(stakeAddress, wallet);
		}
	}
}

window.connectWallet = connectWallet;

function sendAddress(address, wallet){
	document.getElementById('wallet').value = wallet;
	document.getElementById('address').value = address;
	document.getElementById("addressForm").submit();
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

(function ($) {
    const SupportedWallets = [
         'nami',
		 'eternl',
		 'yoroi',
		 'typhoncip30',
		 'gerowallet',
		 'flint',
		 'LodeWallet',
		 'nufi'
    ];

    let retries = 10;
    let loop = null;
    const InstalledWallets = [];

    async function findWallets() {
        if (retries <= 0) {
            clearInterval(loop);
            if (InstalledWallets.length) {
                InstalledWallets.forEach((wallet) => {
					var option = document.createElement("option");
					var displayText = wallet;
					if(displayText == "typhoncip30"){
						displayText = "Typhon";
					}
					option.text = capitalizeFirstLetter(displayText);
					option.value = wallet;
					var select = document.getElementById("wallets");
					select.appendChild(option);
                });
				// Update dropdown with selected wallet after wallet options finish populating
				//updateWallet();
            } else {
            }
        }

        if (window.cardano === undefined) {
            retries--;
            return;
        }

        SupportedWallets.forEach((wallet) => {
            if (window.cardano[wallet] === undefined) {
                return;
            }

            if (!InstalledWallets.includes(wallet)) {
                InstalledWallets.push(wallet);
            }
        });

        retries--;

    }

    $(document).ready(() => {
        loop = setInterval(findWallets, 200);
    });
})(jQuery);