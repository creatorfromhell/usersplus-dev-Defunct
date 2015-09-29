<?php

/**
 * Created by creatorfromhell.
 * Date: 8/22/15
 * Time: 10:06 AM
 * Version: Beta 2
 */
class OAuth {

    public $provider = null;

    public function __construct($parameters = array(), $request = false) {
        try {
            $this->provider = new OAuthProvider($parameters);
            $this->provider->isRequestTokenEndpoint($request);
            $this->provider->consumerHandler(array($this, 'consumer_lookup'));
            if(!$request) {
                $this->provider->tokenHandler(array($this, 'handle_token'));
            }
            $this->provider->timestampNonceHandler(array($this, 'timestamp_nonce_checker'));
        } catch(OAuthException $e) {
            OAuthProvider::reportProblem($e);
        }
    }

    public function consumer_lookup($provider) {

    }

    public function handle_token($provider) {
        if($provider->token == "rejected") {
            return OAUTH_TOKEN_REJECTED;
        } else if($provider->token == "revoked") {
            return OAUTH_TOKEN_REVOKED;
        }
        return OAUTH_OK;
    }

    public function timestamp_nonce_checker($provider) {
        if($provider->timestamp == "0") {
            return OAUTH_BAD_TIMESTAMP;
        } else if($provider->none == "bad") {
            return OAUTH_BAD_NONCE;
        }
        return OAUTH_OK;
    }

    public function action($action = 'undefined') {

    }
}