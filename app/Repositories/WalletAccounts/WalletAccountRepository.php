<?php
namespace App\Repositories\WalletAccounts;

use App\Models\Reservations;
use App\Models\WalletAccount;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;

/**
 * Class BookingsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class WalletAccountRepository extends BaseRepository implements WalletAccountRepositoryInterface
{


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WalletAccount::class;
    }


    public function createWalletAccount(array $data): WalletAccount
    {
       return  $this->create($data);
    }

    public function generateNewBalance($userId = null, $amount_paid=0,  $tranx_type)
    {
        $userId = $userId ?? auth('web')->id();
        $walletAccount = $this->where('user_id', $userId)->latest()->first();
        if($tranx_type === $this->model()::CREDIT_TRANX){
            $balance = ($walletAccount->balance ?? 0) + $amount_paid;
        }
        elseif($tranx_type === $this->model()::DEBIT_TRANX){
            if(isset($walletAccount->balance) && $walletAccount->balance > 0){
                $balance = $walletAccount->balance  - $amount_paid;
            }
            else{
                $balance = 0;
            }
        }
        else{
            $balance = 0;
        }
        return floatval($balance);
    }

    // public function getBalance($userId = null)
    // {
    //     $userId = $userId ?? auth('web')->id();
    //     $walletAccount = $this->where('user_id', $userId)->latest()->first();
    //     return $walletAccount->balance ?? 0;
    // }

    // public function getTotalDeposited($userId = null)
    // {
    //     $query = $this->query();
       
    //     if(isset($userId)){
    //         $userId = $userId ?? auth('web')->id();
    //         $query = $query->where('user_id', $userId);
    //     }
    //     $totalDeposited = $query->sum('credit');
    //     return $totalDeposited ?? 0;
    // }
      
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
