/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package database;

import exceptions.DBException;
import java.util.List;

/**
 *
 * @author manuel, steffen
 */
public interface TimePostingProxy {

    List getTimePosting(int mid) throws DBException;

}