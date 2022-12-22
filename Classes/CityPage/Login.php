<?php

class Login
{
    protected array $userNames;
    protected array $userPasswords;
    protected string $error;
    protected string $algo;

    public function __construct()
    {
        $path = __DIR__."/../../LoginInfo.txt";
        $this->userNames = [];
        $this->userPasswords = [];
        $this->error = "";
        $this->algo = PASSWORD_BCRYPT;
        if (file_exists($path)) {
            $fop = fopen($path, 'r');
            while( !feof($fop) ) {
                $line = trim(fgets($fop, 1020));
                $arrTmp = explode(' ', $line);
                if (count($arrTmp) > 1)
                    {
                        $this->userNames[] = $arrTmp[0];
                        $this->userPasswords["$arrTmp[0]"] = $arrTmp[1];
                    }
            }
            fclose($fop);
        } else {
            $this->error = "file not found";
        }
    }

    public function isUserLogged(string $userName): bool
    {
        foreach ($this->userNames as $nm){
            if ( $nm == password_verify($nm,$userName) ) {
                return true;
            }
        }
        return false;
    }

    public function getCurrentUser(): ?string
    {
        if ( isset($_SESSION['id']) ) {
            return $_SESSION['id'];
        } else {
            return null;
        }
    }

    public function isUser(string $userNm): bool
    {
        return in_array($userNm, $this->userNames)?true:false;
    }

    public function isPasswordCorrect(string $usr, string $pwd): bool
    {
        return password_verify($pwd,$this->userPasswords["$usr"]); //($this->userPasswords["$usr"]==$pwd);
    }

    public function convertPassword(string $pwdOrig): string
    {
        return password_hash($pwdOrig, $this->algo);
    }
}