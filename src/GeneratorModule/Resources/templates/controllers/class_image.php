<?php

namespace __MODULE__\Controller;

use App;
use __MODULE__\UploadImage;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Description of ImageController
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class ImageController 
{
    
    /**
     * List action page
     * 
     * @param App $app
     * @return string
     */
    function index(App $app)
    {
        $table_columns = array(
            'id',
            'name',
            'link',
        );

        $primary_key = "id";
        $rows = array();

        $find_sql = "SELECT * FROM `image`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());

        foreach ($rows_sql as $row_key => $row_sql) {
            for ($i = 0; $i < count($table_columns); $i++) {
                $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
            }
        }

        return $app['twig']->render('image/list.html.twig', array(
            "table_columns" => $table_columns,
            "primary_key" => $primary_key,
            "rows" => $rows
        ));
    }
    
    /**
     * Images list for create product page
     * 
     * @param App $app
     * @return string
     */
    function listJson(App $app)
    {
        $table_columns = array(
            'id',
            'name',
            'link',
        );

        $find_sql = "SELECT * FROM `image`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());

        $images = array();
        
        foreach ($rows_sql as $row_key => $row_sql) {
            for ($i = 0; $i < count($table_columns); $i++) {
                $images[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
            }
        }

        return $app['twig']->render('image/listJson.html.twig', array(
            'images' => $images
        ));
    }
    
    /**
     * Create action page
     * 
     * @param App $app
     * @return string
     */
    function create(App $app)
    {
        if ("POST" == $app['request']->getMethod()) {
            $uploadImage = new UploadImage();
            $uploadImage->upload_dir = $app['upload_dir'];

            $result = $uploadImage->uploadFile($_FILES['image']);

            $vars = $result['vars'];

            $nombreImagen = $vars['imagen'];
            $linkImagen = "$app[upload_path]/$vars[folder]/$vars[imagen]";

            $update_query = "INSERT INTO `image` (`name`, `link`, `created`) VALUES (?, ?, NOW())";
            $app['db']->executeUpdate($update_query, array($nombreImagen, $linkImagen));

            die(json_encode(array('status' => $result['status'])));
        }

        return $app['twig']->render('image/create.html.twig', array());
    }
    
    
    /**
     * Edit action page
     * 
     * @param App $app
     * @param int $id
     * @return RedirectResponse|string
     */
    function edit(App $app, $id)
    {
        $find_sql = "SELECT * FROM `image` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if (!$row_sql) {
            $app['session']->getFlashBag()->add(
                'danger', 
                array(
                    'message' => '¡Imagen no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('imagen_list'));
        }

        $initial_data = array(
            'nombre' => $row_sql['name'],
            'link' => $row_sql['link'],
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('nombre', 'text', array('required' => true));
        $form = $form->add('descripcion', 'textarea', array('required' => false));

        $form = $form->getForm();

        if ("POST" == $app['request']->getMethod()) {

            $form->handleRequest($app["request"]);

            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = "UPDATE `image` SET `name` = ?, `link` = ? WHERE `id` = ?";
                $app['db']->executeUpdate($update_query, array($data['nombre'], $data['link'], $id));

                $app['session']->getFlashBag()->add(
                    'success', 
                    array(
                        'message' => '¡Imagen modificada!',
                    )
                );
                return $app->redirect($app['url_generator']->generate('imagen_edit', array("id" => $id)));
            }
        }

        return $app['twig']->render('image/edit.html.twig', array(
            "form" => $form->createView(),
            "imagen" => $row_sql,
            "id" => $id
        ));
    }
    
    /**
     * Delete action page
     * 
     * @param App $app
     * @param int $id
     * @return RedirectResponse
     */
    function delete(App $app, $id)
    {
        $find_sql = "SELECT * FROM `image` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if ($row_sql) {

            // Eliminar archivos
            $search_str = $app['upload_path'] . "/";
            $file = $app['upload_dir'] . str_replace($search_str, '', $row_sql['link']);

            // Eliminar los archivos

            $delete_query = "DELETE FROM `image` WHERE `id` = ?";
            $app['db']->executeUpdate($delete_query, array($id));

            $app['session']->getFlashBag()->add(
                'success', 
                array(
                    'message' => '¡Imagen eliminada!',
                )
            );
        } else {
            $app['session']->getFlashBag()->add(
                'danger', 
                array(
                    'message' => '¡Imagen no encontrada!',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('imagen_list'));
    }
    
}
