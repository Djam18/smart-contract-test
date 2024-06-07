import Web3 from 'web3';
import { argv } from 'process';

const address = "0x45448338125083d111240939CFd841D034a7468c";
const provider = "https://lb.drpc.org/ogrpc?network=polygon&dkey=Ak9zschOOkuPkClELxQ3MAczFFHQJDoR75__hkHL9tz4";

let web3Provider = new Web3.providers.HttpProvider(provider);
const web3 = new Web3(web3Provider);

(async () => {
    try {
        web3.eth.getBalance(address).then((result) => console.log(web3.utils.fromWei(result, 'ether')));
    } catch (error) {
        console.error(error);
        throw error;
    }
})();

