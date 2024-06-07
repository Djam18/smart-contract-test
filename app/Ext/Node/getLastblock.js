import Web3 from 'web3';
import { argv } from 'process';

let provider = "https://lb.drpc.org/ogrpc?network=polygon&dkey=Ak9zschOOkuPkClELxQ3MAczFFHQJDoR75__hkHL9tz4";
let web3Provider = new Web3.providers.HttpProvider(provider);
let web3 = new Web3(web3Provider);

(async function getBlockNumber() {
    try {
        // Get the latest block number
        web3.eth.getBlockNumber().then((result) => {
            console.log("Latest Ethereum Block is ", result);
        });
    } catch (error) {
        console.error(error);
        throw error;
    }
})();
