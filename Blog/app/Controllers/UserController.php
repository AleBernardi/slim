<?php


namespace  App\Controllers;

use Slim\Http\UploadedFile;


class UserController extends Controller {



    public function avatar($request, $response){
        if($request->isGet())
            return $this->container->view->render($response, 'user/avatar.twig');

        $directory = $this->container->upload_directory;

        $avatar = $request->getUploadedFiles()['avatar'];

        if(!$avatar->getError()) {
            $filename = $this->moveUploadedFile($directory, $avatar);
            $user = $this->container->auth->user();
            $user->avatar = $filename;
            $user->save();

            $this->container->flash->addMessage('success', 'Avatar enviado com sucesso!');
        } else {
            $this->container->flash->addMessage('error', 'Houve um erro ao tentar enviar o avatar.');
        }

        return $response->withRedirect($this->container->router->pathFor('user.avatar'));
    }

    private function moveUploadedFile($directory, UploadedFile $uploadedFile){
        $extention = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extention);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}