<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\Auth\InvalidCaptcha;
use Closure;
use Illuminate\Http\Request;
use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;

class HasValidCaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle( Request $request, Closure $next, string $action )
    {
        // Disable captcha by removing env key
        $site_key = env( 'GOOGLE_CAPTCHA_KEY', null );
        if ( $site_key == null ) {
            return $next( $request );
        }

        $token = $request->input( 'recaptcha_token', null );
        if ( $token == null ) {
            throw new InvalidCaptcha();
        }

        $this->create_assessment( $site_key, $token, $action );

        return $next($request);
    }

    private function create_assessment(
        string $site_key,
        string $token,
        string $action,
     ): void {
        
        $client = new RecaptchaEnterpriseServiceClient([
            'credentials' => base_path( env( "GOOGLE_APPLICATION_CREDENTIALS" ) )
        ]);
        $projectName = $client->projectName( "aktulibre" );
   
        $event = (new Event())
            ->setSiteKey( $site_key )
            ->setToken($token);
   
        $assessment = (new Assessment())
            ->setEvent($event);
   
        try {
            $response = $client->createAssessment(
                $projectName,
                $assessment
            );
        } catch ( \Exception $err ) {
            // Could not perform api call
        } finally {
            $client->close();
        }

        if ( $response == null ) {
            throw new InvalidCaptcha( "Could not verify captcha via Google." );
        }

        // You can use the score only if the assessment is valid,
        // In case of failures like re-submitting the same token, getValid() will return false
        if ($response->getTokenProperties()->getValid() == false) {
            $reason = InvalidReason::name( $response->getTokenProperties()->getInvalidReason() );
            throw new InvalidCaptcha( $reason );
        } else {
            $score = $response->getRiskAnalysis()->getScore();
            
            if ( $response->getTokenProperties()->getAction() !== $action ) {
                throw new InvalidCaptcha( "Captcha was used for the wrong action." );
            }

            if ( $score < 0.5 ) {
                throw new InvalidCaptcha();
            }
        }
        
     }
   
}
