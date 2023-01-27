<?php
namespace Classes\CityPage;

use Classes\Base\AccessDB;

class Login
{
    protected array $userNames;
    protected array $userPasswords;
    //protected string $error;
    protected string $algo;

    protected AccessDB $activeUsersDB;
    protected array $activeUsersShortList;

    public function __construct()
    {
        $path = __DIR__."/../../LoginInfo.txt";
        $this->userNames = [];
        $this->userPasswords = [];
        $this->error = "";
        $this->algo = PASSWORD_BCRYPT;
        try {
            if (!file_exists($path)) {
                throw new Exception('file '.$path.' is not found');
            }
            $fop = fopen($path, 'r');
            while( !feof($fop) ) {
                $line = trim(fgets($fop, 1020));
                $arrTmp = explode(' ', $line);
                if (count($arrTmp) > 1) {
                    $this->userNames[] = $arrTmp[0];
                    $this->userPasswords["$arrTmp[0]"] = $arrTmp[1];
                }
            }
            fclose($fop);

            $this->activeUsersDB = new AccessDB();
            $this->activeUsersShortList = [];
            $tmp = $this->activeUsersDB->query('SELECT id, time FROM `activeUsers`',[]);
            foreach ($tmp as $actUser)
            {
                $this->activeUsersShortList[] = $actUser["id"];
            }
            $this->activeUsersShortList = array_unique($this->activeUsersShortList,SORT_REGULAR);
        } catch (Throwable $e) {
            echo 'Admin panel is not accessible at the moment'.$e->getMessage();
        }
    }

    public function isUserLogged(string $userName): bool
    {
        foreach ($this->activeUsersShortList as $nm){
            if ( $nm == password_verify($nm,$userName) ) {
                return true;
            }
        }
        return false;
    }

    public function isUser(string $userNm): bool
    {
        return in_array($userNm, $this->userNames)?true:false;
    }

    public function isPasswordCorrect(string $usr, string $pwd): bool
    {
        return password_verify($pwd,$this->userPasswords["$usr"]);
    }

    public function convertPassword(string $pwdOrig): string
    {
        return password_hash($pwdOrig, $this->algo);
    }

    public function addActiveUserDB(string $usrName): void
    {
        if ( !in_array($usrName, $this->activeUsersShortList)) {
            $this->activeUsersShortList[] = $usrName;
            $this->activeUsersDB->execute('INSERT INTO `activeUsers`(`id`, `time`) VALUES ("'.$usrName.'", CURRENT_TIMESTAMP)');
        }
    }

    public function removeActiveUser(string $userNameHashed): void
    {
        foreach ($this->activeUsersShortList as $nm)
        {
            if ( $nm == password_verify($nm,$userNameHashed) ) {
                $this->activeUsersDB->execute('DELETE FROM `activeUsers` WHERE id = "'.$nm.'"');
                $this->activeUsersShortList = array_diff($this->activeUsersShortList, [$nm]);
                return;
            }
        }
    }
}