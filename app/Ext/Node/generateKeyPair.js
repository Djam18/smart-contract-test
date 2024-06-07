import Web3 from 'web3';

let provider = "https://lb.drpc.org/ogrpc?network=polygon&dkey=Ak9zschOOkuPkClELxQ3MAczFFHQJDoR75__hkHL9tz4";
let web3Provider = new Web3.providers.HttpProvider(provider);
let web3 = new Web3(web3Provider);

(async function generateKeyPair() {
    try {
        const account = web3.eth.accounts.create();
        const wallet = web3.eth.accounts.wallet.add(account);
        console.log(JSON.stringify(wallet));
    } catch (error) {
        console.error(error);
        throw error;
    }
})();
