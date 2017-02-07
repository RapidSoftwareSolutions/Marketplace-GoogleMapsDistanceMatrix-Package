<p align="left">
  <a href="http://rapidapi.com">
    <img src="https://storage.googleapis.com/rapidapi-temp/background.png"/>
  </a>
</p>

# Google Maps Distance Matrix
Provides travel distance and time for a matrix of origins and destinations.
* Domain: google.com
* Credentials: apiKey

## How to get credentials: 
1. Go to the [Google API Console](https://console.developers.google.com/flows/enableapi?apiid=distance_matrix_backend&reusekey=true)
2. Create or select a project.
3. Click Continue to enable the API.
4. On the Credentials page, get an API key (and set the API key restrictions). 
Note: If you have an existing unrestricted API key, or a key with server restrictions, you may use that key.


You can also [look up an existing key](https://console.developers.google.com/project/_/apiui/credential) in the Google API Console.

For more information on using the Google API Console, see [API Console Help](https://support.google.com/googleapi).

## GoogleMapsDistanceMatrix.getBicyclingDistanceMatrix
Requests distance calculation for bicycling via bicycle paths & preferred streets (where available)

| Field       | Type       | Description
|-------------|------------|----------
| apiKey      | credentials| The api key obtained from Google Developers Console.
| origins     | String     | The starting point for calculating travel distance and time. You can supply one or more locations separated by the pipe character, in the form of an address, latitude/longitude coordinates, or a place ID
| destinations| String     | One or more locations to use as the finishing point for calculating travel distance and time. The options for the destinations parameter are the same as for the origins parameter, described above
| language    | String     | The language in which to return results. List of supported languages: `https://developers.google.com/maps/faq#languagesupport`
| units       | String     |  Specifies the unit system to use when expressing distance as text. Possible values are: metric or imperial

## GoogleMapsDistanceMatrix.getWalkingDistanceMatrix
Requests distance calculation for walking via pedestrian paths & sidewalks (where available).

| Field       | Type       | Description
|-------------|------------|----------
| apiKey      | credentials| The api key obtained from Google Developers Console.
| origins     | String     | The starting point for calculating travel distance and time. You can supply one or more locations separated by the pipe character, in the form of an address, latitude/longitude coordinates, or a place ID
| destinations| String     | One or more locations to use as the finishing point for calculating travel distance and time. The options for the destinations parameter are the same as for the origins parameter, described above
| language    | String     | The language in which to return results. List of supported languages: https://developers.google.com/maps/faq#languagesupport
| units       | String     |  Specifies the unit system to use when expressing distance as text. Possible values are: metric or imperial

## GoogleMapsDistanceMatrix.getDrivingDistanceMatrix
Indicates distance calculation using the road network.

| Field         | Type       | Description
|---------------|------------|----------
| apiKey        | credentials| The api key obtained from Google Developers Console.
| origins       | String     | The starting point for calculating travel distance and time. You can supply one or more locations separated by the pipe character, in the form of an address, latitude/longitude coordinates, or a place ID
| destinations  | String     | One or more locations to use as the finishing point for calculating travel distance and time. The options for the destinations parameter are the same as for the origins parameter, described above
| language      | String     | The language in which to return results. List of supported languages: https://developers.google.com/maps/faq#languagesupport
| units         | String     | Specifies the unit system to use when expressing distance as text. Possible values are: metric or imperial
| avoid         | String     | Introduces restrictions to the route. Possible values: tolls or highways or ferries or indoor
| departureTime| String     | The desired time of departure. You can specify the time as an integer in seconds since midnight, January 1, 1970 UTC. Alternatively, you can specify a value of now, which sets the departure time to the current time (correct to the nearest second).
| trafficModel | String     | Specifies the assumptions to use when calculating time in traffic. The traffic_model parameter may only be specified for requests where the request includes a departure_time. Possible values: best_guess or pessimistic or optimistic

## GoogleMapsDistanceMatrix.getTransitDistanceMatrix
Requests distance calculation via public transit routes (where available).

| Field                     | Type       | Description
|---------------------------|------------|----------
| apiKey                    | credentials| The api key obtained from Google Developers Console.
| origins                   | String     | The starting point for calculating travel distance and time. You can supply one or more locations separated by the pipe character, in the form of an address, latitude/longitude coordinates, or a place ID
| destinations              | String     | One or more locations to use as the finishing point for calculating travel distance and time. The options for the destinations parameter are the same as for the origins parameter, described above
| language                  | String     | The language in which to return results. List of supported languages: https://developers.google.com/maps/faq#languagesupport
| units                     | String     | Specifies the unit system to use when expressing distance as text. Possible values are: metric or imperial
| transitMode              | String     | Specifies one or more preferred modes of transit. Possible values are: bus or subway or train or tram or rail (indicates that the calculated route should prefer travel by train, tram, light rail, and subway. This is equivalent to `transit_mode=train or transit_mode=tram or transit_mode=subway`)
| transitRoutingPreference| String     | Specifies preferences for transit requests. Possible values are: less_walking or fewer_transfers
| timeRestriction          | String     | Specifies the desired time of departure or arrival (but not both). You can specify the time as an integer in seconds since midnight, January 1, 1970 UTC. `Possible values are: departure_time=12345 or arrival_time=12345`

